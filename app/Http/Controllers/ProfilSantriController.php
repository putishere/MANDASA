<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfilSantriController extends Controller
{
    public function index()
    {
        // Refresh user dari database dengan eager loading relasi santriDetail
        // Ini memastikan data selalu terbaru dan sesuai dengan yang diinput
        $user = User::with('santriDetail')->findOrFail(auth()->id());
        
        // Verifikasi bahwa user memiliki santriDetail
        if (!$user->santriDetail) {
            \Log::warning('Santri profil accessed but no santriDetail found', [
                'user_id' => $user->id,
                'username' => $user->username
            ]);
            
            return redirect()->route('login')
                ->withErrors(['error' => 'Data santri tidak lengkap. Silakan hubungi admin.']);
        }
        
        $detail = $user->santriDetail;
        
        return view('profil-santri.index', compact('user', 'detail'));
    }

    public function print()
    {
        // Refresh user dari database dengan eager loading relasi santriDetail
        $user = User::with('santriDetail')->findOrFail(auth()->id());
        
        if (!$user->santriDetail) {
            return redirect()->route('santri.profil')
                ->withErrors(['error' => 'Data santri tidak lengkap.']);
        }
        
        $detail = $user->santriDetail;
        
        return view('profil-santri.print', compact('user', 'detail'));
    }

    public function download()
    {
        // Untuk download PDF, perlu install dompdf terlebih dahulu
        // Sementara redirect ke print view
        return redirect()->route('santri.profil.print');
    }
}
