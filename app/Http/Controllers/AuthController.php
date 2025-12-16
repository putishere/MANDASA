<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan form login unified (admin & santri)
     * 
     * Method ini hanya dipanggil jika user BELUM login (dilindungi middleware 'guest').
     * Jika user sudah login, middleware 'guest' akan otomatis redirect ke dashboard sesuai role.
     * 
     * Alur:
     * 1. User mengakses / atau /login
     * 2. Middleware 'guest' mengecek apakah user sudah login
     * 3. Jika sudah login → Redirect ke dashboard (ditangani oleh middleware)
     * 4. Jika belum login → Tampilkan form login
     */
    public function showLogin()
    {
        // Middleware 'guest' sudah menangani redirect jika sudah login
        // Jadi kita hanya perlu menampilkan form login
        return view('auth.login');
    }

    /**
     * Proses login unified - auto detect berdasarkan input (username & password)
     * 
     * Alur Login:
     * 1. Validasi input (username dan password wajib)
     * 2. Auto-detect berdasarkan format input:
     *    - Jika input adalah EMAIL (mengandung @) → Coba login sebagai ADMIN
     *    - Jika input adalah USERNAME → Coba login sebagai SANTRI
     * 3. Jika login berhasil:
     *    - Admin: Auth::attempt() sudah regenerate session otomatis → Langsung redirect
     *    - Santri: Auth::login() perlu regenerate manual → Regenerate lalu redirect
     * 4. Jika login gagal → Kembali ke form dengan error message
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $username = trim($request->input('username'));
            $password = trim($request->input('password'));

            // Deteksi: jika username adalah email (mengandung @), coba login sebagai admin
            // Pastikan validasi email tidak terpengaruh whitespace
            $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);
            $adminLoginAttempted = false;
            $adminUserExists = false;
            
            if ($isEmail) {
                $adminLoginAttempted = true;
                
                // Cek apakah email ada di database (case-insensitive dan trim)
                $adminUser = User::where(function($query) use ($username) {
                    $query->whereRaw('LOWER(TRIM(email)) = ?', [strtolower(trim($username))])
                          ->orWhereRaw('TRIM(email) = ?', [trim($username)])
                          ->orWhere('email', trim($username));
                })->first();
                $adminUserExists = $adminUser !== null;
                
                if ($adminUserExists) {
                    // Coba login sebagai admin dengan email & password
                    $credentials = [
                        'email' => $username,
                        'password' => $password
                    ];

                    // Coba login sebagai admin
                    // Auth::attempt() akan otomatis melakukan session regeneration jika berhasil
                    if (Auth::attempt($credentials, false)) {
                        $user = Auth::user();
                        $userRole = strtolower(trim($user->role ?? ''));
                        
                        // Pastikan role adalah 'admin' (normalisasi dan perbaiki jika perlu)
                        if (empty($userRole) || $userRole === '') {
                            \Log::warning('Admin login - role is empty, fixing', [
                                'user_id' => $user->id,
                                'email' => $user->email
                            ]);
                            $user->role = 'admin';
                            $user->save();
                            $userRole = 'admin';
                        }
                        
                        if ($userRole === 'admin') {
                            // Auth::attempt() sudah melakukan session regeneration otomatis
                            // regenerate() sudah melakukan regenerateToken() otomatis
                            // Tidak perlu regenerate lagi untuk menghindari error 419
                            
                            // Redirect LANGSUNG ke admin dashboard sesuai role (tidak pakai intended)
                            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
                        } else {
                            \Log::warning('Admin login failed - role mismatch', [
                                'user_id' => $user->id,
                                'expected_role' => 'admin',
                                'actual_role' => $userRole
                            ]);
                            Auth::logout();
                            // Jika role bukan admin, lanjutkan ke cek santri
                        }
                    } else {
                        // Email ditemukan tapi password salah
                        return back()->withErrors(['error' => 'Password salah untuk email admin.'])->withInput();
                    }
                } else {
                    // Email tidak ditemukan sebagai admin, coba sebagai santri
                    // (mungkin ada kasus dimana email juga digunakan sebagai username santri)
                }
            }
            
            // Jika login admin gagal atau bukan email, coba login sebagai santri
            // Santri menggunakan username & password (dimana password adalah tanggal lahir dalam format YYYY-MM-DD)
            $santriResult = $this->trySantriLogin($username, $password, $request);
            if ($santriResult !== null) {
                return $santriResult;
            }
            
            // Jika semua metode gagal, berikan pesan yang lebih spesifik
            if ($adminLoginAttempted && $adminUserExists) {
                // Sudah di-handle di atas (password salah)
                return back()->withErrors(['error' => 'Password salah untuk email admin.'])->withInput();
            } else if ($adminLoginAttempted && !$adminUserExists) {
                // Email tidak ditemukan sebagai admin
                return back()->withErrors(['error' => 'Email tidak ditemukan. Pastikan email sudah terdaftar sebagai admin.'])->withInput();
            } else {
                // Cek apakah username ditemukan sebagai santri tapi password salah
                $santriUserExists = User::where(function($query) use ($username) {
                    $query->whereRaw('LOWER(TRIM(username)) = ?', [strtolower(trim($username))])
                          ->orWhereRaw('TRIM(username) = ?', [trim($username)]);
                })->where(function($query) {
                    $query->whereRaw('LOWER(TRIM(role)) = ?', ['santri'])
                          ->orWhere('role', 'santri');
                })->exists();
                
                if ($santriUserExists) {
                    return back()->withErrors(['error' => 'Password salah. Password adalah tanggal lahir dalam format YYYY-MM-DD (contoh: 2005-09-14).'])->withInput();
                } else {
                    return back()->withErrors(['error' => 'Username atau email tidak ditemukan. Pastikan sudah terdaftar sebagai admin (email) atau santri (username).'])->withInput();
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    // Helper method untuk mencoba login santri
    private function trySantriLogin($username, $password, $request)
    {
        // Normalisasi username: trim dan case-insensitive search
        $usernameNormalized = trim($username);
        
        if (empty($usernameNormalized)) {
            return null;
        }
        
        // Login santri dengan username dan password (password adalah tanggal lahir)
        // Cari user dengan username (case-insensitive) dan role santri (case-insensitive)
        // Coba beberapa cara pencarian untuk memastikan menemukan user
        $user = User::where(function($query) use ($usernameNormalized) {
                $query->whereRaw('LOWER(TRIM(username)) = ?', [strtolower($usernameNormalized)])
                      ->orWhereRaw('TRIM(username) = ?', [$usernameNormalized]);
            })
            ->where(function($query) {
                $query->whereRaw('LOWER(TRIM(role)) = ?', ['santri'])
                      ->orWhere('role', 'santri');
            })
            ->with('santriDetail')
            ->first();

        if ($user) {
            // Normalisasi password: trim whitespace
            $passwordNormalized = trim($password);
            
            // Cek password dengan beberapa format yang mungkin:
            // 1. Format asli yang diinput user
            // 2. Format Y-m-d (jika user input d-m-Y atau d/m/Y)
            // 3. Format dari tanggal_lahir di database (jika ada)
            $passwordMatch = false;
            
            // Coba format asli yang diinput user
            $passwordMatch = \Illuminate\Support\Facades\Hash::check($passwordNormalized, $user->password);
            
            // Jika tidak cocok, coba konversi format tanggal
            if (!$passwordMatch && $user->tanggal_lahir) {
                // Coba format Y-m-d dari tanggal_lahir di database
                $tanggalLahirFormatted = $user->tanggal_lahir->format('Y-m-d');
                $passwordMatch = \Illuminate\Support\Facades\Hash::check($tanggalLahirFormatted, $user->password);
                
                // Jika password di database tidak sesuai dengan tanggal_lahir, perbaiki
                if (!$passwordMatch) {
                    // Cek apakah password yang di-hash sesuai dengan tanggal lahir
                    // Jika tidak, kemungkinan password disimpan dengan format yang berbeda
                    // Perbaiki password di database agar sesuai dengan tanggal_lahir
                    $user->password = \Illuminate\Support\Facades\Hash::make($tanggalLahirFormatted);
                    $user->save();
                    $user->refresh();
                    // Coba lagi dengan password yang baru diperbaiki menggunakan input user
                    $passwordMatch = \Illuminate\Support\Facades\Hash::check($passwordNormalized, $user->password);
                    // Jika masih tidak cocok, coba dengan format tanggal lahir
                    if (!$passwordMatch) {
                        $passwordMatch = \Illuminate\Support\Facades\Hash::check($tanggalLahirFormatted, $user->password);
                    }
                }
                
                // Jika masih tidak cocok, coba parse format lain yang mungkin diinput user
                if (!$passwordMatch) {
                    // Coba format d-m-Y jika user input seperti "14-09-2005"
                    try {
                        $dateParts = explode('-', $passwordNormalized);
                        if (count($dateParts) === 3) {
                            // Jika format d-m-Y, konversi ke Y-m-d
                            if (strlen($dateParts[2]) === 4 && strlen($dateParts[0]) <= 2) {
                                $convertedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
                                $passwordMatch = \Illuminate\Support\Facades\Hash::check($convertedDate, $user->password);
                            }
                        }
                        
                        // Coba format d/m/Y jika user input seperti "14/09/2005"
                        if (!$passwordMatch) {
                            $dateParts = explode('/', $passwordNormalized);
                            if (count($dateParts) === 3) {
                                if (strlen($dateParts[2]) === 4 && strlen($dateParts[0]) <= 2) {
                                    $convertedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
                                    $passwordMatch = \Illuminate\Support\Facades\Hash::check($convertedDate, $user->password);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // Ignore parsing errors
                    }
                }
            }
            
            if ($passwordMatch) {
                // Pastikan user memiliki santriDetail
                if (!$user->santriDetail) {
                    \Log::warning('Santri login attempted but no santriDetail found', [
                        'user_id' => $user->id,
                        'username' => $user->username
                    ]);
                    return back()->withErrors(['error' => 'Anda belum terdaftar sebagai santri. Silakan hubungi admin untuk pendaftaran.'])->withInput();
                }
                
                // Pastikan role adalah 'santri' sebelum login (normalisasi dan perbaiki jika perlu)
                $userRole = strtolower(trim($user->role ?? ''));
                
                if ($userRole !== 'santri') {
                    \Log::warning('Santri login - role mismatch, fixing', [
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'current_role' => $user->role,
                        'normalized_role' => $userRole
                    ]);
                    
                    // Perbaiki role di database
                    $user->role = 'santri';
                    $user->save();
                    $userRole = 'santri';
                }
                
                // Refresh user dari database untuk memastikan data terbaru sebelum login
                $user->refresh();
                
                // Login berhasil
                // Gunakan Auth::login() - perlu regenerate session karena tidak otomatis
                Auth::login($user, false); // false = tidak remember me
                
                // Regenerate session setelah login (Auth::login() tidak melakukan regenerate otomatis)
                // regenerate() sudah melakukan regenerateToken() otomatis
                // Tidak perlu regenerateToken() lagi untuk menghindari error 419
                $request->session()->regenerate();
                
                // Verifikasi user yang sudah login dan role-nya
                // Refresh user dari database untuk memastikan data terbaru
                $loggedInUser = Auth::user();
                $loggedInUser->refresh();
                $finalRole = strtolower(trim($loggedInUser->role ?? ''));
                
                // Log untuk debugging
                \Log::info('Santri login successful', [
                    'user_id' => $loggedInUser->id,
                    'username' => $loggedInUser->username,
                    'role' => $finalRole,
                    'has_santri_detail' => $loggedInUser->santriDetail ? true : false
                ]);
                
                // Pastikan role sudah benar sebelum redirect
                if ($finalRole !== 'santri') {
                    \Log::error('Santri login - role mismatch after login, fixing', [
                        'user_id' => $loggedInUser->id,
                        'expected_role' => 'santri',
                        'actual_role' => $finalRole,
                        'raw_role' => $loggedInUser->role
                    ]);
                    
                    // Perbaiki role dan refresh lagi
                    $loggedInUser->role = 'santri';
                    $loggedInUser->save();
                    $loggedInUser->refresh();
                }
                
                // Redirect LANGSUNG ke santri dashboard sesuai role
                // Gunakan redirect()->route() untuk memastikan redirect yang benar
                return redirect()->route('santri.dashboard')->with('success', 'Login berhasil!');
            } else {
                \Log::warning('Santri login failed - password mismatch', [
                    'username' => $usernameNormalized,
                    'user_id' => $user->id,
                    'tanggal_lahir_db' => $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : null,
                    'password_input' => $passwordNormalized ?? $password
                ]);
            }
        } else {
            \Log::debug('Santri login failed - user not found', [
                'username' => $usernameNormalized,
                'searched_username' => strtolower($usernameNormalized)
            ]);
        }

        // Return null untuk menandakan login santri gagal
        return null;
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}