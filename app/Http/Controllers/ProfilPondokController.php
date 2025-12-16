<?php

namespace App\Http\Controllers;

use App\Models\ProfilPondok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilPondokController extends Controller
{
    public function index()
    {
        // Refresh untuk memastikan data terbaru
        $profil = ProfilPondok::getInstance()->fresh();
        return view('profil-pondok.index', compact('profil'));
    }

    public function edit()
    {
        $profil = ProfilPondok::getInstance();
        return view('profil-pondok.edit', compact('profil'));
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_pondok' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:255',
                'tentang' => 'nullable|string',
                'visi' => 'nullable|string',
                'misi' => 'nullable|string',
                'program_unggulan' => 'nullable|string',
                'fasilitas' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $profil = ProfilPondok::getInstance();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($profil->logo && Storage::disk('public')->exists($profil->logo)) {
                    Storage::disk('public')->delete($profil->logo);
                }
                $validated['logo'] = $request->file('logo')->store('profil-pondok', 'public');
            } else {
                unset($validated['logo']);
            }

            $profil->update($validated);

            return redirect()->route('admin.profil-pondok')
                ->with('success', 'Profil pondok berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }
}
