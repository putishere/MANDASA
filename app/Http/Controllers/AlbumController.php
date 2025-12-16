<?php

namespace App\Http\Controllers;

use App\Models\AlbumPondok;
use App\Models\AlbumFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = AlbumPondok::active()
            ->with('fotos')
            ->orderBy('urutan')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('album.index', compact('albums'));
    }

    public function manage()
    {
        $albums = AlbumPondok::with(['coverFoto', 'fotos' => function($q) {
            $q->orderBy('urutan')->orderBy('created_at');
        }])->orderBy('urutan')->orderBy('created_at', 'desc')->paginate(12);
        $kategoriOptions = AlbumPondok::getKategoriOptions();
        return view('admin.album.manage', compact('albums', 'kategoriOptions'));
    }

    public function create()
    {
        $kategoriOptions = AlbumPondok::getKategoriOptions();
        return view('admin.album.create', compact('kategoriOptions'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
                'kategori' => 'required|string|in:umum,belajar,ngaji,olahraga,keagamaan,sosial,acara',
                'urutan' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
            ], [
                'foto.required' => 'Foto wajib diupload.',
                'foto.image' => 'File harus berupa gambar (JPEG, PNG, JPG, atau GIF).',
                'foto.mimes' => 'Format file harus JPEG, PNG, JPG, atau GIF.',
                'foto.max' => 'Ukuran foto maksimal 10MB. Silakan kompres atau pilih foto yang lebih kecil.',
            ]);

            // Buat album tanpa foto (foto akan masuk ke tabel album_fotos)
            $albumData = [
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'kategori' => $validated['kategori'],
                'urutan' => $validated['urutan'] ?? 0,
                'is_active' => $request->has('is_active') ? true : false,
                'foto' => '', // Kosongkan karena akan menggunakan album_fotos
            ];

            $album = AlbumPondok::create($albumData);

            // Handle foto upload ke tabel album_fotos
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('album-pondok', 'public');
                
                AlbumFoto::create([
                    'album_pondok_id' => $album->id,
                    'foto' => $fotoPath,
                    'judul' => null,
                    'deskripsi' => null,
                    'urutan' => 0,
                    'is_cover' => true, // Foto pertama menjadi cover
                ]);
            }

            return redirect()->route('admin.album.show', $album->id)
                ->with('success', 'Album berhasil dibuat. Silakan tambah foto lainnya.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(string $id)
    {
        $album = AlbumPondok::findOrFail($id);
        $kategoriOptions = AlbumPondok::getKategoriOptions();
        return view('admin.album.edit', compact('album', 'kategoriOptions'));
    }

    public function update(Request $request, string $id)
    {
        try {
            $album = AlbumPondok::findOrFail($id);

            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'kategori' => 'required|string|in:umum,belajar,ngaji,olahraga,keagamaan,sosial,acara',
                'urutan' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
            ], [
                'foto.image' => 'File harus berupa gambar (JPEG, PNG, JPG, atau GIF).',
                'foto.mimes' => 'Format file harus JPEG, PNG, JPG, atau GIF.',
                'foto.max' => 'Ukuran foto maksimal 10MB. Silakan kompres atau pilih foto yang lebih kecil.',
            ]);

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto if exists
                if ($album->foto && Storage::disk('public')->exists($album->foto)) {
                    Storage::disk('public')->delete($album->foto);
                }
                $validated['foto'] = $request->file('foto')->store('album-pondok', 'public');
            } else {
                unset($validated['foto']);
            }

            $validated['is_active'] = $request->has('is_active') ? true : false;
            $validated['urutan'] = $validated['urutan'] ?? $album->urutan;

            $album->update($validated);

            return redirect()->route('admin.album.manage')
                ->with('success', 'Foto album berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(string $id)
    {
        $album = AlbumPondok::with(['fotos' => function($query) {
            $query->orderByRaw('CASE WHEN is_cover = 1 THEN 0 ELSE 1 END')
                  ->orderBy('urutan')
                  ->orderBy('created_at');
        }])->findOrFail($id);
        $kategoriOptions = AlbumPondok::getKategoriOptions();
        return view('admin.album.show', compact('album', 'kategoriOptions'));
    }

    public function storeFoto(Request $request, string $id)
    {
        try {
            $album = AlbumPondok::findOrFail($id);
            
            $validated = $request->validate([
                'fotos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
                'judul' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string',
            ], [
                'fotos.*.required' => 'Foto wajib diupload.',
                'fotos.*.image' => 'File harus berupa gambar.',
                'fotos.*.mimes' => 'Format file harus JPEG, PNG, JPG, atau GIF.',
                'fotos.*.max' => 'Ukuran foto maksimal 10MB.',
            ]);

            $uploadedCount = 0;
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $fotoPath = $foto->store('album-pondok', 'public');
                    
                    AlbumFoto::create([
                        'album_pondok_id' => $album->id,
                        'foto' => $fotoPath,
                        'judul' => $validated['judul'] ?? null,
                        'deskripsi' => $validated['deskripsi'] ?? null,
                        'urutan' => $album->fotos()->max('urutan') + 1 ?? 0,
                        'is_cover' => $album->fotos()->count() == 0, // Set sebagai cover jika ini foto pertama
                    ]);
                    $uploadedCount++;
                }
            }

            return redirect()->route('admin.album.show', $album->id)
                ->with('success', $uploadedCount . ' foto berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function updateFoto(Request $request, string $albumId, string $fotoId)
    {
        try {
            $foto = AlbumFoto::where('album_pondok_id', $albumId)->findOrFail($fotoId);
            
            $validated = $request->validate([
                'judul' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            ]);

            if ($request->hasFile('foto')) {
                if ($foto->foto && Storage::disk('public')->exists($foto->foto)) {
                    Storage::disk('public')->delete($foto->foto);
                }
                $validated['foto'] = $request->file('foto')->store('album-pondok', 'public');
            } else {
                unset($validated['foto']);
            }

            $foto->update($validated);

            return redirect()->route('admin.album.show', $albumId)
                ->with('success', 'Foto berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function destroyFoto(string $albumId, string $fotoId)
    {
        try {
            $foto = AlbumFoto::where('album_pondok_id', $albumId)->findOrFail($fotoId);
            
            if ($foto->foto && Storage::disk('public')->exists($foto->foto)) {
                Storage::disk('public')->delete($foto->foto);
            }

            $foto->delete();

            return redirect()->route('admin.album.show', $albumId)
                ->with('success', 'Foto berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function setCover(string $albumId, string $fotoId)
    {
        try {
            $album = AlbumPondok::findOrFail($albumId);
            $foto = AlbumFoto::where('album_pondok_id', $albumId)->findOrFail($fotoId);
            
            // Set semua foto is_cover menjadi false
            AlbumFoto::where('album_pondok_id', $albumId)->update(['is_cover' => false]);
            
            // Set foto yang dipilih sebagai cover
            $foto->update(['is_cover' => true]);

            return redirect()->route('admin.album.show', $albumId)
                ->with('success', 'Foto profil album berhasil diubah.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $album = AlbumPondok::with('fotos')->findOrFail($id);

            // Delete semua foto dalam album
            foreach ($album->fotos as $foto) {
                if ($foto->foto && Storage::disk('public')->exists($foto->foto)) {
                    Storage::disk('public')->delete($foto->foto);
                }
            }

            // Delete foto lama jika ada
            if ($album->foto && Storage::disk('public')->exists($album->foto)) {
                Storage::disk('public')->delete($album->foto);
            }

            $album->delete();

            return redirect()->route('admin.album.manage')
                ->with('success', 'Album berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.album.manage')
                ->withErrors(['error' => 'Terjadi kesalahan saat menghapus: ' . $e->getMessage()]);
        }
    }
}

