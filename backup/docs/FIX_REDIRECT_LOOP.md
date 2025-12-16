# Perbaikan Error ERR_TOO_MANY_REDIRECTS

## Masalah

Error "ERR_TOO_MANY_REDIRECTS" atau "terlalu sering mengalihkan" terjadi ketika mengakses `/santri/dashboard` atau route lainnya.

## Penyebab

1. **Role user di database tidak tepat** (ada spasi, case berbeda, atau null)
2. **Redirect loop di middleware** karena role tidak sesuai
3. **Session atau cookie yang corrupt**

## Solusi

### Langkah 1: Perbaiki Role User di Database

Jalankan script perbaikan:

```bash
php fix_role_user.php
```

Atau perbaiki manual melalui tinker:

```bash
php artisan tinker
```

Kemudian jalankan:

```php
// Cek semua user dan role
User::all()->map(function($u) {
    return [
        'id' => $u->id,
        'name' => $u->name,
        'email' => $u->email,
        'username' => $u->username,
        'role' => $u->role,
        'role_length' => strlen($u->role ?? ''),
        'role_trimmed' => trim($u->role ?? ''),
    ];
});

// Perbaiki role yang bermasalah
User::where('role', '!=', 'admin')
    ->where('role', '!=', 'santri')
    ->orWhereNull('role')
    ->get()
    ->each(function($user) {
        // Tentukan role berdasarkan data
        if ($user->email && !$user->username) {
            $user->role = 'admin';
        } elseif ($user->username) {
            $user->role = 'santri';
        } else {
            $user->role = 'admin'; // default
        }
        $user->save();
        echo "User {$user->id} ({$user->name}) diperbaiki menjadi: {$user->role}\n";
    });

// Normalisasi role (pastikan lowercase, no spaces)
User::all()->each(function($user) {
    $role = strtolower(trim($user->role ?? ''));
    if (in_array($role, ['admin', 'santri'])) {
        if ($user->role !== $role) {
            $user->role = $role;
            $user->save();
            echo "User {$user->id} role dinormalisasi: {$user->role} -> {$role}\n";
        }
    }
});
```

### Langkah 2: Clear Cache dan Session

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Langkah 3: Hapus Cookie Browser

1. Buka Developer Tools (F12)
2. Buka tab **Application** (Chrome) atau **Storage** (Firefox)
3. Di bagian **Cookies**, pilih `http://127.0.0.1:8000`
4. Hapus semua cookie, terutama:
   - `laravel_session`
   - `XSRF-TOKEN`
5. Refresh halaman atau gunakan mode **Incognito/Private Browsing**

### Langkah 4: Clear Session di Database (jika menggunakan database session)

Jika menggunakan `SESSION_DRIVER=database`:

```bash
php artisan tinker
```

```php
DB::table('sessions')->truncate();
```

Atau langsung di database:

```sql
TRUNCATE TABLE sessions;
```

### Langkah 5: Verifikasi Role User

Pastikan semua user memiliki role yang benar:

```bash
php artisan tinker
```

```php
// Cek semua user
User::all()->each(function($u) {
    $role = strtolower(trim($u->role ?? ''));
    if (!in_array($role, ['admin', 'santri'])) {
        echo "WARNING: User {$u->id} ({$u->name}) memiliki role tidak valid: '{$u->role}'\n";
    } else {
        echo "OK: User {$u->id} ({$u->name}) role: '{$u->role}'\n";
    }
});
```

## Perbaikan yang Sudah Dilakukan

1. ✅ **Middleware EnsureUserRole** - Diperbaiki untuk mencegah redirect loop
   - Menambahkan pengecekan route name untuk mencegah loop
   - Menambahkan logging untuk debugging
   - Auto-logout jika role tidak valid

2. ✅ **AuthController** - Diperbaiki validasi role saat login santri
   - Memastikan role adalah 'santri' sebelum login
   - Menambahkan error handling yang lebih baik

3. ✅ **Script Perbaikan** - Dibuat `fix_role_user.php`
   - Script untuk memperbaiki role user secara otomatis
   - Menampilkan status semua user

## Cara Mencegah Masalah Ini

1. **Pastikan role selalu lowercase dan tanpa spasi** saat membuat user baru
2. **Gunakan enum atau validation** untuk memastikan role hanya 'admin' atau 'santri'
3. **Jangan edit role langsung di database** tanpa memastikan formatnya benar
4. **Gunakan seeder** untuk membuat user default dengan role yang benar

## Contoh Role yang Benar

✅ **Benar:**
- `admin`
- `santri`

❌ **Salah:**
- `Admin` (huruf besar)
- `ADMIN` (semua huruf besar)
- ` admin ` (ada spasi)
- `santri ` (ada spasi di akhir)
- `admin ` (ada spasi di akhir)
- `null` atau kosong

## Test Setelah Perbaikan

1. Clear cache dan session
2. Hapus cookie browser
3. Login sebagai admin: `admin@pondok.test` / `admin123`
4. Pastikan redirect ke `/admin/dashboard` tanpa error
5. Logout
6. Login sebagai santri (jika ada data)
7. Pastikan redirect ke `/santri/dashboard` tanpa error

## Jika Masih Bermasalah

1. Cek log Laravel: `storage/logs/laravel.log`
2. Cek role user di database secara langsung
3. Pastikan middleware terdaftar di `app/Http/Kernel.php`
4. Pastikan route terdaftar dengan benar di `routes/web.php`
5. Cek apakah ada middleware lain yang menyebabkan redirect

## Catatan

- Middleware sekarang akan auto-logout user jika role tidak valid
- Logging ditambahkan untuk membantu debugging
- Script `fix_role_user.php` dapat dijalankan kapan saja untuk memperbaiki role

