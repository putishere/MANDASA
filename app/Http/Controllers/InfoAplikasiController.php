<?php

namespace App\Http\Controllers;

use App\Models\InfoAplikasi;
use Illuminate\Http\Request;

class InfoAplikasiController extends Controller
{
    public function index()
    {
        // Refresh untuk memastikan data terbaru
        $info = InfoAplikasi::getInstance()->fresh();
        return view('info-aplikasi.index', compact('info'));
    }

    public function edit()
    {
        $info = InfoAplikasi::getInstance();
        return view('info-aplikasi.edit', compact('info'));
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'tentang' => 'nullable|string',
                'fitur' => 'nullable|string',
                'keamanan' => 'nullable|string',
                'bantuan' => 'nullable|string',
                'versi' => 'required|string|max:50',
                'framework' => 'required|string|max:100',
                'database' => 'required|string|max:100',
            ]);

            $info = InfoAplikasi::getInstance();
            $info->update($validated);

            return redirect()->route('admin.info-aplikasi')
                ->with('success', 'Info aplikasi berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }
}
