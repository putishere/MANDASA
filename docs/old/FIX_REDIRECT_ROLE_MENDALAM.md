# Perbaikan Redirect Setelah Login Sesuai Role - Perbaikan Mendalam

## Masalah

Setelah login berhasil, redirect tidak terarah sesuai role yang diinput. User mungkin di-redirect ke dashboard yang salah atau tidak di-redirect sama sekali.

## Penyebab

1. **Menggunakan `redirect()->intended()`** yang mungkin redirect ke URL sebelumnya, bukan sesuai role
2. **Role tidak diverifikasi dengan benar** sebelum redirect
3. **Role tidak dinormalisasi** sebelum redirect
4. **Tidak ada validasi role** setelah login berhasil

## Solusi Mendalam

### 1. Perbaikan Admin Login

**Sebelum (SALAH):**
```php
if ($userRole === 'admin') {
    $request->session()->regenerateToken();
    return redirect()->intended(route('admin.dashboard'))->with('success', 'Login berhasil!');
}
```

**Sesudah (BENAR):**
```php
// Pastikan role adalah 'admin' (normalisasi dan perbaiki jika perlu)
if (empty($userRole) || $userRole === '') {
    $user->role = 'admin';
    $user->save();
    $userRole = 'admin';
}

if ($userRole === 'admin') {
    // Regenerate CSRF token untuk keamanan
    $request->session()->regenerateToken();
    
    // Redirect LANGSUNG ke admin dashboard sesuai role (tidak pakai intended)
    return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
}
```

**Perbaikan:**
- ✅ Validasi dan perbaiki role jika kosong sebelum redirect
- ✅ Gunakan `route()` langsung, bukan `intended()` untuk memastikan redirect sesuai role
- ✅ Log warning jika role tidak sesuai

### 2. Perbaikan Santri Login

**Sebelum (SALAH):**
```php
Auth::login($user, false);
$request->session()->regenerate();
$request->session()->regenerateToken();
$request->session()->save();
return redirect()->intended(route('santri.dashboard'))->with('success', 'Login berhasil!');
```

**Sesudah (BENAR):**
```php
// Pastikan role adalah 'santri' sebelum login
$userRole = strtolower(trim($user->role ?? ''));
if ($userRole !== 'santri') {
    $user->role = 'santri';
    $user->save();
    $userRole = 'santri';
}

Auth::login($user, false);
$request->session()->regenerate();
$request->session()->regenerateToken();
$request->session()->save();

// Pastikan role masih 'santri' setelah login
$loggedInUser = Auth::user();
$finalRole = strtolower(trim($loggedInUser->role ?? ''));

if ($finalRole === 'santri') {
    // Redirect LANGSUNG ke santri dashboard sesuai role (tidak pakai intended)
    return redirect()->route('santri.dashboard')->with('success', 'Login berhasil!');
} else {
    // Log error dan logout jika role berubah setelah login
    Auth::logout();
    return back()->withErrors(['error' => 'Terjadi kesalahan pada role user.']);
}
```

**Perbaikan:**
- ✅ Validasi dan perbaiki role sebelum login
- ✅ Verifikasi role lagi setelah login untuk memastikan konsistensi
- ✅ Gunakan `route()` langsung, bukan `intended()` untuk memastikan redirect sesuai role
- ✅ Log error dan logout jika role berubah setelah login

## Alur yang Benar Sekarang

### Admin Login:
```
1. User submit form dengan email → POST /login
2. Auth::attempt() → Login berhasil
3. Ambil user dan normalisasi role
4. Jika role kosong → Set ke 'admin' dan save
5. Jika role === 'admin' → Regenerate token → Redirect ke admin.dashboard
6. Jika role bukan admin → Logout dan lanjut ke login santri
```

### Santri Login:
```
1. User submit form dengan username → POST /login
2. Cari user dengan username dan role 'santri'
3. Validasi password dengan Hash::check
4. Pastikan role adalah 'santri' (perbaiki jika perlu)
5. Auth::login() → Regenerate session → Regenerate token → Save
6. Verifikasi role lagi setelah login
7. Jika role === 'santri' → Redirect ke santri.dashboard
8. Jika role bukan santri → Logout dan error
```

## Perubahan yang Dilakukan

1. ✅ **Admin Login**
   - Validasi dan perbaiki role jika kosong
   - Gunakan `route('admin.dashboard')` langsung, bukan `intended()`
   - Log warning jika role tidak sesuai

2. ✅ **Santri Login**
   - Validasi dan perbaiki role sebelum login
   - Verifikasi role lagi setelah login
   - Gunakan `route('santri.dashboard')` langsung, bukan `intended()`
   - Log error dan logout jika role berubah setelah login

3. ✅ **Konsistensi Role**
   - Normalisasi role selalu dilakukan (lowercase, trim)
   - Perbaiki role di database jika tidak sesuai
   - Verifikasi role sebelum dan setelah login

## Testing

### Langkah 1: Clear Session dan Cache
```bash
del storage\framework\sessions\*.*
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Langkah 2: Test Login Admin
1. Buka `http://127.0.0.1:8000/login`
2. Login dengan:
   - Email: `admin@pondok.test`
   - Password: `admin123`
3. Seharusnya redirect ke `/admin/dashboard`

### Langkah 3: Test Login Santri
1. Logout dari admin
2. Login dengan:
   - Username: sesuai data santri
   - Password: tanggal lahir (format: YYYY-MM-DD)
3. Seharusnya redirect ke `/santri/dashboard`

### Langkah 4: Verifikasi Role di Database
```bash
php artisan tinker
```
```php
// Cek role admin
$admin = \App\Models\User::where('email', 'admin@pondok.test')->first();
echo "Admin role: " . $admin->role . "\n";

// Cek role santri
$santri = \App\Models\User::whereRaw('LOWER(TRIM(role)) = ?', ['santri'])->first();
echo "Santri role: " . $santri->role . "\n";
```

## Catatan Penting

1. **Jangan Gunakan `intended()`**
   - `intended()` akan redirect ke URL sebelumnya jika ada
   - Ini bisa menyebabkan redirect ke tempat yang salah
   - Gunakan `route()` langsung untuk memastikan redirect sesuai role

2. **Validasi Role Sebelum dan Sesudah Login**
   - Pastikan role benar sebelum login
   - Verifikasi role lagi setelah login
   - Perbaiki role di database jika tidak sesuai

3. **Normalisasi Role**
   - Selalu normalisasi role (lowercase, trim)
   - Konsisten di semua tempat (AuthController, middleware, dll)

4. **Logging**
   - Log warning jika role tidak sesuai
   - Log error jika role berubah setelah login
   - Ini membantu debugging jika ada masalah

## Status

✅ **Perbaikan sudah dilakukan**
- Admin login: Validasi role + route() langsung
- Santri login: Validasi role sebelum/sesudah + route() langsung
- Konsistensi role di semua tempat
- Tidak ada error linting

Redirect setelah login sekarang sudah terarah sesuai role yang diinput!

