<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SantriDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('users.role', 'santri')
            ->leftJoin('santri_detail', 'users.id', '=', 'santri_detail.user_id')
            ->select('users.*')
            ->with('santriDetail');
        
        // Pencarian berdasarkan keyword
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'LIKE', "%{$search}%")
                  ->orWhere('users.username', 'LIKE', "%{$search}%")
                  ->orWhere('santri_detail.nis', 'LIKE', "%{$search}%")
                  ->orWhere('santri_detail.alamat_santri', 'LIKE', "%{$search}%")
                  ->orWhere('santri_detail.nama_wali', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('santri_detail.status_santri', $request->status);
        }
        
        // Urutkan berdasarkan NIS (ascending)
        // Prioritas: NIS yang null/kosong di akhir, lalu urutkan secara natural
        // Kompatibel dengan SQLite dan MySQL
        $dbDriver = \DB::connection()->getDriverName();
        
        if ($dbDriver === 'sqlite') {
            // Query untuk SQLite (tidak support REGEXP)
            // Gunakan query sederhana yang kompatibel dengan SQLite
            $santri = $query->orderByRaw('
                CASE 
                    WHEN santri_detail.nis IS NULL OR santri_detail.nis = "" THEN 1 
                    ELSE 0 
                END ASC,
                santri_detail.nis ASC
            ')->paginate(10)->withQueryString();
        } else {
            // Query untuk MySQL/MariaDB (support REGEXP)
            $santri = $query->orderByRaw('
                CASE 
                    WHEN santri_detail.nis IS NULL OR santri_detail.nis = "" THEN 1 
                    ELSE 0 
                END ASC,
                CASE 
                    WHEN santri_detail.nis REGEXP "^[0-9]+$" THEN CAST(santri_detail.nis AS UNSIGNED)
                    ELSE 999999999
                END ASC,
                santri_detail.nis ASC
            ')->paginate(10)->withQueryString();
        }
        
        return view('santri.index', compact('santri'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('santri.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Validasi data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|unique:users,username|max:255',
                'tanggal_lahir' => 'required|date',
                'tahun_masuk' => 'required|integer|min:2000|max:' . (date('Y') + 1),
                'nis' => 'required|string|unique:santri_detail,nis|max:255',
                'alamat_santri' => 'required|string',
                'nomor_hp_santri' => 'nullable|string|max:20',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,jpeg|max:10240',
                'status_santri' => 'required|in:aktif,boyong',
                'nama_wali' => 'required|string|max:255',
                'alamat_wali' => 'required|string',
                'nomor_hp_wali' => 'nullable|string|max:20',
            ], [
                'foto.max' => 'Ukuran foto maksimal 10MB. Silakan pilih foto yang lebih kecil.',
                'foto.image' => 'File yang diupload harus berupa gambar (JPEG, PNG, atau JPG).',
                'foto.mimes' => 'Format foto yang diizinkan: JPEG, PNG, atau JPG.',
            ]);

            Log::info('Store santri - Validasi berhasil', ['username' => $validated['username'], 'nis' => $validated['nis']]);

            // Normalisasi username: trim whitespace
            $usernameNormalized = trim($validated['username']);
            
            // Create user (santri)
            $user = User::create([
                'name' => trim($validated['name']),
                'username' => $usernameNormalized,
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'role' => 'santri', // Pastikan role lowercase
                'password' => Hash::make($validated['tanggal_lahir']), // default password adalah tanggal lahir
            ]);

            // Verifikasi user berhasil dibuat
            if (!$user || !$user->id) {
                throw new \Exception('Gagal membuat user. User tidak tersimpan.');
            }

            Log::info('Store santri - User created', ['user_id' => $user->id, 'username' => $user->username]);

            // Handle foto upload
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                try {
                    $fotoPath = $request->file('foto')->store('santri/fotos', 'public');
                    Log::info('Store santri - Foto uploaded', ['foto_path' => $fotoPath]);
                } catch (\Exception $e) {
                    Log::warning('Store santri - Foto upload gagal', ['error' => $e->getMessage()]);
                    // Foto upload gagal tidak menghentikan proses, lanjutkan tanpa foto
                }
            }

            // Create santri detail
            $santriDetail = SantriDetail::create([
                'user_id' => $user->id,
                'nis' => $validated['nis'],
                'tahun_masuk' => $validated['tahun_masuk'],
                'alamat_santri' => $validated['alamat_santri'],
                'nomor_hp_santri' => $validated['nomor_hp_santri'],
                'foto' => $fotoPath,
                'status_santri' => $validated['status_santri'],
                'nama_wali' => $validated['nama_wali'],
                'alamat_wali' => $validated['alamat_wali'],
                'nomor_hp_wali' => $validated['nomor_hp_wali'],
            ]);

            // Verifikasi santri detail berhasil dibuat
            if (!$santriDetail || !$santriDetail->id) {
                throw new \Exception('Gagal membuat santri detail. Data tidak tersimpan.');
            }

            Log::info('Store santri - SantriDetail created', ['santri_detail_id' => $santriDetail->id, 'user_id' => $user->id]);

            // Verifikasi final: cek apakah data benar-benar ada di database
            $userCheck = User::find($user->id);
            $detailCheck = SantriDetail::find($santriDetail->id);
            
            if (!$userCheck) {
                throw new \Exception('User tidak ditemukan setelah penyimpanan. Data mungkin tidak tersimpan.');
            }
            
            if (!$detailCheck) {
                throw new \Exception('Santri detail tidak ditemukan setelah penyimpanan. Data mungkin tidak tersimpan.');
            }

            // Commit transaction jika semua berhasil
            DB::commit();

            Log::info('Store santri - Berhasil', ['user_id' => $user->id, 'santri_detail_id' => $santriDetail->id]);

            return redirect()->route('santri.index')
                ->with('success', 'Data santri berhasil ditambahkan.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::warning('Store santri - Validasi gagal', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store santri - Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['password', '_token'])
            ]);
            
            $errorMessage = 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage();
            if (config('app.debug')) {
                $errorMessage .= ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine();
            }
            
            return back()->withErrors(['error' => $errorMessage])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $santri = User::where('role', 'santri')
            ->with('santriDetail')
            ->findOrFail($id);
        
        return view('santri.show', compact('santri'));
    }

    /**
     * Download PDF of santri detail.
     */
    public function downloadPDF(string $id, Request $request)
    {
        $santri = User::where('role', 'santri')
            ->with('santriDetail')
            ->findOrFail($id);
        
        $html = view('santri.pdf', compact('santri'))->render();
        
        // Jika parameter download=1, langsung download sebagai file
        if ($request->has('download') && $request->download == '1') {
            $filename = 'Data_Santri_' . str_replace(' ', '_', $santri->name) . '_' . date('Y-m-d') . '.html';
            
            return response()->make($html, 200, [
                'Content-Type' => 'text/html',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }
        
        // Default: tampilkan di browser
        return response()->make($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="Data_Santri_' . str_replace(' ', '_', $santri->name) . '.html"',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $santri = User::where('role', 'santri')
            ->with('santriDetail')
            ->findOrFail($id);
        
        return view('santri.edit', compact('santri'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $santri = User::where('role', 'santri')
                ->with('santriDetail')
                ->findOrFail($id);

            $santriDetailId = $santri->santriDetail ? $santri->santriDetail->id : null;
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|unique:users,username,' . $id . '|max:255',
                'tanggal_lahir' => 'required|date',
                'tahun_masuk' => 'required|integer|min:2000|max:' . (date('Y') + 1),
                'nis' => 'required|string|unique:santri_detail,nis,' . $santriDetailId . '|max:255',
                'alamat_santri' => 'required|string',
                'nomor_hp_santri' => 'nullable|string|max:20',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,jpeg|max:10240',
                'status_santri' => 'required|in:aktif,boyong',
                'nama_wali' => 'required|string|max:255',
                'alamat_wali' => 'required|string',
                'nomor_hp_wali' => 'nullable|string|max:20',
            ], [
                'foto.max' => 'Ukuran foto maksimal 10MB. Silakan pilih foto yang lebih kecil.',
                'foto.image' => 'File yang diupload harus berupa gambar (JPEG, PNG, atau JPG).',
                'foto.mimes' => 'Format foto yang diizinkan: JPEG, PNG, atau JPG.',
            ]);

            // Normalisasi username: trim whitespace
            $usernameNormalized = trim($validated['username']);
            
            // Update user
            $updateData = [
                'name' => trim($validated['name']),
                'username' => $usernameNormalized,
                'tanggal_lahir' => $validated['tanggal_lahir'],
            ];
            
            // Update password jika tanggal lahir berubah (karena password default adalah tanggal lahir)
            if ($santri->tanggal_lahir != $validated['tanggal_lahir']) {
                $updateData['password'] = Hash::make($validated['tanggal_lahir']);
            }
            
            $santri->update($updateData);

            // Handle foto upload
            $fotoPath = $santri->santriDetail->foto ?? null;
            if ($request->hasFile('foto')) {
                // Delete old foto if exists
                if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                    Storage::disk('public')->delete($fotoPath);
                }
                $fotoPath = $request->file('foto')->store('santri/fotos', 'public');
            }

            // Update or create santri detail
            if ($santri->santriDetail) {
                $santri->santriDetail->update([
                    'nis' => $validated['nis'],
                    'tahun_masuk' => $validated['tahun_masuk'],
                    'alamat_santri' => $validated['alamat_santri'],
                    'nomor_hp_santri' => $validated['nomor_hp_santri'],
                    'foto' => $fotoPath,
                    'status_santri' => $validated['status_santri'],
                    'nama_wali' => $validated['nama_wali'],
                    'alamat_wali' => $validated['alamat_wali'],
                    'nomor_hp_wali' => $validated['nomor_hp_wali'],
                ]);
            } else {
                SantriDetail::create([
                    'user_id' => $santri->id,
                    'nis' => $validated['nis'],
                    'tahun_masuk' => $validated['tahun_masuk'],
                    'alamat_santri' => $validated['alamat_santri'],
                    'nomor_hp_santri' => $validated['nomor_hp_santri'],
                    'foto' => $fotoPath,
                    'status_santri' => $validated['status_santri'],
                    'nama_wali' => $validated['nama_wali'],
                    'alamat_wali' => $validated['alamat_wali'],
                    'nomor_hp_wali' => $validated['nomor_hp_wali'],
                ]);
            }

            return redirect()->route('santri.index')
                ->with('success', 'Data santri berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $santri = User::where('role', 'santri')
                ->with('santriDetail')
                ->findOrFail($id);

            // Delete foto if exists
            if ($santri->santriDetail && $santri->santriDetail->foto) {
                Storage::disk('public')->delete($santri->santriDetail->foto);
            }

            // Delete santri detail (cascade will handle this, but explicit is better)
            if ($santri->santriDetail) {
                $santri->santriDetail->delete();
            }

            // Delete user
            $santri->delete();

            return redirect()->route('santri.index')
                ->with('success', 'Data santri berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('santri.index')
                ->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }
}

