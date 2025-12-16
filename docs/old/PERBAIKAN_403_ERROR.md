# Perbaikan Error 403 - Akses Ditolak

## Masalah
Error 403 "AKSES DITOLAK. ROLE TIDAK VALID UNTUK HALAMAN INI." terjadi ketika user mencoba mengakses dashboard mereka.

## Penyebab
1. Middleware `EnsureUserRole` melakukan `abort(403)` ketika user sudah di dashboard yang benar
2. Role user di database tidak ter-normalisasi dengan benar
3. Logika middleware yang terlalu ketat

## Perbaikan yang Telah Dilakukan

### 1. Middleware `EnsureUserRole`
**Sebelum:**
- Melakukan `abort(403)` ketika user sudah di dashboard yang benar
- Logika yang membingungkan

**Sesudah:**
- Menghapus `abort(403)` yang tidak perlu
- Redirect ke dashboard yang sesuai jika role tidak sesuai
- Logika yang lebih jelas dan aman

### 2. AuthController
**Perbaikan:**
- Normalisasi role saat login admin
- Auto-fix role saat login santri jika role tidak sesuai
- Memastikan role selalu lowercase dan trim

### 3. Script Perbaikan
- `fix_user_role.php` - Script untuk memperbaiki role user di database

## Cara Mengatasi Error 403

### Langkah 1: Perbaiki Role User di Database
Jalankan script perbaikan:

```bash
php fix_user_role.php
```

**Atau manual dengan Tinker:**
```bash
php artisan tinker
```

```php
// Normalisasi semua role
User::all()->each(function($user) {
    $user->role = strtolower(trim($user->role ?? ''));
    if (empty($user->role) || !in_array($user->role, ['admin', 'santri'])) {
        $user->role = $user->email ? 'admin' : 'santri';
    }
    $user->save();
});
```

### Langkah 2: Clear Cache dan Session
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

**Hapus session files:**
```powershell
Remove-Item "storage\framework\sessions\*" -Exclude ".gitignore" -Force
```

### Langkah 3: Hapus Cookie Browser
1. Buka DevTools (F12)
2. Tab **Application** → **Cookies** → `http://127.0.0.1:8000`
3. Klik **Clear All**
4. Refresh halaman (Ctrl + F5)

### Langkah 4: Test Login
1. Logout dari aplikasi
2. Login ulang dengan credentials yang benar
3. Pastikan redirect ke dashboard yang sesuai

## Verifikasi Perbaikan

### Test Case 1: Login sebagai Admin
1. Login dengan email admin: `admin@pondok.com` / `admin123`
2. Harus redirect ke `/admin/dashboard`
3. Tidak boleh ada error 403

### Test Case 2: Login sebagai Santri
1. Login dengan username santri dan password (tanggal lahir)
2. Harus redirect ke `/santri/dashboard`
3. Tidak boleh ada error 403

### Test Case 3: Akses Route yang Salah
1. Admin mencoba akses `/santri/dashboard` → Redirect ke `/admin/dashboard`
2. Santri mencoba akses `/admin/dashboard` → Redirect ke `/santri/dashboard`
3. Tidak boleh ada error 403, hanya redirect

## Troubleshooting

### Jika Masih Error 403:

1. **Cek Role User di Database:**
```php
// Di Tinker
$user = User::find(USER_ID);
echo "Role: " . $user->role . "\n";
echo "Normalized: " . strtolower(trim($user->role ?? '')) . "\n";
```

2. **Pastikan Role Valid:**
- Role harus: `'admin'` atau `'santri'` (lowercase, tanpa spasi)
- Jangan: `'Admin'`, `'ADMIN'`, `' admin '`, atau `null`

3. **Clear Semua Cache:**
```bash
php artisan optimize:clear
```

4. **Restart Server:**
```bash
# Stop server (Ctrl + C)
php artisan serve
```

## Catatan Penting

- **Role harus selalu lowercase** - 'admin' atau 'santri', bukan 'Admin' atau 'ADMIN'
- **Tidak boleh ada whitespace** - pastikan role di database sudah di-trim
- **Tidak boleh null** - setiap user harus memiliki role yang valid
- **Auto-fix saat login** - jika role tidak sesuai, akan otomatis diperbaiki saat login santri

## Status Perbaikan

✅ Middleware `EnsureUserRole` sudah diperbaiki
✅ AuthController sudah diperbaiki dengan normalisasi role
✅ Script perbaikan role sudah tersedia
✅ Error handling sudah diperbaiki

**Aplikasi sekarang seharusnya berfungsi tanpa error 403!**

