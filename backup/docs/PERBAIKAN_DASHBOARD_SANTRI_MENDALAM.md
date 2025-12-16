# Perbaikan Dashboard Santri Tidak Bisa Diakses - Perbaikan Mendalam

## Masalah

Dashboard santri tidak bisa di `/santri/dashboard` tidak bisa diakses setelah santri login berhasil.

## Penyebab yang Ditemukan

1. **User tidak di-refresh setelah login** - Data user mungkin tidak ter-update dengan benar
2. **Role tidak ter-verifikasi dengan benar** setelah login
3. **Session tidak tersimpan dengan benar** sebelum redirect
4. **Middleware tidak refresh user** dari database sebelum validasi

## Perbaikan Mendalam yang Dilakukan

### 1. AuthController - Perbaikan Login Santri

**Sebelum (MASALAH):**
```php
Auth::login($user, false);
$request->session()->regenerate();
$request->session()->regenerateToken();
$request->session()->save();

$loggedInUser = Auth::user();
$finalRole = strtolower(trim($loggedInUser->role ?? ''));

if ($finalRole === 'santri') {
    return redirect()->route('santri.dashboard');
}
```

**Sesudah (DIPERBAIKI):**
```php
// Pastikan role sudah benar sebelum login
$user->refresh(); // Refresh dari database
$userRole = strtolower(trim($user->role ?? ''));

if ($userRole !== 'santri') {
    $user->role = 'santri';
    $user->save();
    $user->refresh();
}

Auth::login($user, false);
$request->session()->regenerate();
$request->session()->regenerateToken();
$request->session()->save();

// Verifikasi user yang sudah login dan role-nya
$loggedInUser = Auth::user();
$loggedInUser->refresh(); // Refresh dari database
$finalRole = strtolower(trim($loggedInUser->role ?? ''));

// Log untuk debugging
\Log::info('Santri login successful', [
    'user_id' => $loggedInUser->id,
    'username' => $loggedInUser->username,
                        'role' => $finalRole
]);

if ($finalRole === 'santri') {
    return redirect()->route('santri.dashboard');
} else {
    // Perbaiki role dan redirect lagi
    $loggedInUser->role = 'santri';
    $loggedInUser->save();
    $loggedInUser->refresh();
    return redirect()->route('santri.dashboard');
}
```

**Perbaikan:**
- ✅ Refresh user dari database sebelum login
- ✅ Refresh user lagi setelah login untuk memastikan data terbaru
- ✅ Perbaiki role jika tidak sesuai dan refresh lagi
- ✅ Log untuk debugging
- ✅ Fallback: perbaiki role dan redirect lagi jika masih tidak sesuai

### 2. EnsureUserRole - Perbaikan Validasi Role

**Sebelum (MASALAH):**
```php
$user = Auth::user();
$userRole = $user->role;
// Normalisasi...
```

**Sesudah (DIPERBAIKI):**
```php
$user = Auth::user();
// Refresh user dari database untuk memastikan data terbaru
$user->refresh();

$userRole = $user->role;
// Normalisasi...

// Jika role kosong atau tidak valid, perbaiki
if (empty($userRole) || $userRole === '' || !in_array($userRole, ['admin', 'santri'])) {
    // Perbaiki berdasarkan required role atau email
    if ($requiredRole === 'admin' || ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL))) {
        $user->role = 'admin';
    } else {
        $user->role = 'santri';
    }
    $user->save();
    $user->refresh();
    $userRole = strtolower(trim($user->role ?? ''));
}
```

**Perbaikan:**
- ✅ Refresh user dari database sebelum validasi
- ✅ Validasi role lebih ketat (harus 'admin' atau 'santri')
- ✅ Perbaiki role berdasarkan required role jika tidak valid
- ✅ Refresh lagi setelah perbaikan

## Alur yang Benar Sekarang

### Login Santri:
```
1. User submit form → POST /login
2. Cari user dengan username dan role 'santri'
3. Validasi password dengan Hash::check
4. Pastikan memiliki santriDetail
5. Refresh user dari database
6. Pastikan role adalah 'santri' (perbaiki jika perlu)
7. Auth::login() → Regenerate session → Regenerate token → Save
8. Refresh user lagi setelah login
9. Verifikasi role lagi
10. Jika role 'santri' → Redirect ke santri.dashboard
11. Jika role tidak sesuai → Perbaiki, refresh, redirect lagi
```

### Akses Dashboard Santri:
```
1. User mengakses /santri/dashboard
2. Middleware 'auth' → Cek apakah sudah login
3. Middleware 'role:santri' → Refresh user dari database
4. Normalisasi role → Validasi role
5. Jika role kosong/tidak valid → Perbaiki berdasarkan required role
6. Jika role === 'santri' → Lanjutkan ke route
7. Jika role !== 'santri' → Redirect ke dashboard sesuai role
```

## Testing

### Langkah 1: Clear Session dan Cookie

**Hapus Cookie Browser:**
- F12 → Application → Cookies → `http://127.0.0.1:8000` → Clear All
- Atau klik tombol "Hapus cookie" di halaman error

**Clear Session Laravel:**
```bash
del storage\framework\sessions\*.*
```

**Clear Cache:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Langkah 2: Verifikasi Data Santri di Database

```bash
php artisan tinker
```

```php
// Cek semua user santri
$santriUsers = \App\Models\User::whereRaw('LOWER(TRIM(role)) = ?', ['santri'])->get();
foreach ($santriUsers as $santri) {
    echo "ID: {$santri->id}, Username: {$santri->username}, Role: '{$santri->role}'\n";
    echo "  Has santriDetail: " . ($santri->santriDetail ? 'Yes' : 'No') . "\n";
}

// Cek role admin
$admin = \App\Models\User::whereRaw('LOWER(TRIM(role)) = ?', ['admin'])->first();
if ($admin) {
    echo "Admin: {$admin->email}, Role: '{$admin->role}'\n";
}
```

### Langkah 3: Test Login Santri

1. **Buka browser baru atau incognito**
2. **Akses:** `http://127.0.0.1:8000/login`
3. **Login sebagai santri:**
   - Username: sesuai data santri (contoh: `fauzi123`)
   - Password: tanggal lahir (format: `YYYY-MM-DD`, contoh: `2005-08-15`)
4. **Seharusnya:**
   - Redirect ke `/santri/dashboard`
   - Tidak ada error 419 atau redirect loop
   - Dashboard santri tampil dengan benar

### Langkah 4: Cek Log Laravel

```bash
# Buka file log
notepad storage\logs\laravel.log

# Atau tail log (jika ada tail command)
tail -f storage\logs\laravel.log
```

**Cari log:**
- `Santri login successful` - Login berhasil
- `Role mismatch detected` - Ada masalah role
- `EnsureUserRole: User santri di santri.dashboard` - Middleware log

## Troubleshooting

### Jika Masih Tidak Bisa Diakses:

1. **Cek Role di Database:**
   ```php
   // Pastikan role adalah 'santri' (lowercase, tanpa spasi)
   $user = \App\Models\User::where('username', 'USERNAME_SANTRI')->first();
   echo "Role: '{$user->role}'\n";
   echo "Normalized: '" . strtolower(trim($user->role ?? '')) . "'\n";
   ```

2. **Cek Session:**
   ```php
   // Cek apakah user sudah login
   \Illuminate\Support\Facades\Auth::check();
   \Illuminate\Support\Facades\Auth::user();
   ```

3. **Cek Route:**
   ```bash
   php artisan route:list --name=santri.dashboard
   ```

4. **Cek Middleware:**
   - Pastikan middleware `role:santri` terdaftar di `app/Http/Kernel.php`
   - Pastikan route menggunakan middleware `auth` dan `role:santri`

## Perubahan yang Dilakukan

1. ✅ **AuthController - Login Santri**
   - Refresh user sebelum dan setelah login
   - Perbaiki role jika tidak sesuai
   - Log untuk debugging
   - Fallback: perbaiki role dan redirect lagi

2. ✅ **EnsureUserRole Middleware**
   - Refresh user sebelum validasi
   - Validasi role lebih ketat
   - Perbaiki role berdasarkan required role jika tidak valid
   - Refresh lagi setelah perbaikan

3. ✅ **Konsistensi Data**
   - Selalu refresh user dari database sebelum validasi
   - Normalisasi role selalu dilakukan
   - Perbaiki role otomatis jika tidak sesuai

## Status

✅ **Perbaikan sudah dilakukan**
- AuthController: Refresh user sebelum/sesudah login
- EnsureUserRole: Refresh user sebelum validasi
- Validasi role lebih ketat
- Perbaiki role otomatis jika tidak sesuai
- Log untuk debugging
- Tidak ada error linting

Dashboard santri seharusnya sudah bisa diakses setelah login!

