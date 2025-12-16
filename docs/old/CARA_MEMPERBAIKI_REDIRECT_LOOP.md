# Cara Memperbaiki Redirect Loop (ERR_TOO_MANY_REDIRECTS)

## ⚠️ Masalah
Error **"ERR_TOO_MANY_REDIRECTS"** atau **"127.0.0.1 terlalu sering mengalihkan Anda"** terjadi karena aplikasi terus-menerus melakukan redirect.

## ✅ Solusi Cepat (Lakukan Semua Langkah)

### Langkah 1: Hapus Cookie Browser
**PENTING:** Ini adalah langkah paling penting!

**Cara 1 - Dari Halaman Error:**
1. Di halaman error, klik tombol **"Hapus cookie"** (Delete cookie)
2. Klik **"Refresh"**

**Cara 2 - Manual di Browser:**

**Chrome/Edge:**
1. Tekan `F12` untuk buka DevTools
2. Buka tab **Application** (Chrome) atau **Storage** (Edge)
3. Di sidebar kiri, klik **Cookies** → `http://127.0.0.1:8000`
4. Klik kanan pada setiap cookie → **Delete** atau klik **Clear All**
5. Tutup DevTools dan refresh halaman (`Ctrl + F5`)

**Firefox:**
1. Tekan `F12` untuk buka DevTools
2. Buka tab **Storage**
3. Klik **Cookies** → `http://127.0.0.1:8000`
4. Klik kanan → **Delete All**
5. Refresh halaman (`Ctrl + F5`)

### Langkah 2: Clear Browser Cache
1. Tekan `Ctrl + Shift + Delete`
2. Pilih:
   - ✅ Cookies and other site data
   - ✅ Cached images and files
3. Pilih **All time**
4. Klik **Clear data**

### Langkah 3: Hapus Session Files
Buka terminal di folder project dan jalankan:

**Windows (PowerShell):**
```powershell
# Hapus semua file session
Remove-Item "storage\framework\sessions\*" -Exclude ".gitignore" -Force
```

**Atau manual:**
1. Buka folder: `storage\framework\sessions\`
2. Hapus semua file di dalamnya (kecuali `.gitignore`)

### Langkah 4: Clear Cache Laravel
Buka terminal Laragon atau terminal dengan PHP di PATH, lalu jalankan:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

**Jika PHP tidak di PATH (Laragon):**
1. Buka terminal Laragon
2. Atau buka PowerShell di folder project
3. Jalankan perintah di atas

### Langkah 5: Perbaiki Role User di Database
Jalankan script perbaikan:

```bash
php fix_all_issues.php
```

**Atau manual dengan Tinker:**
```bash
php artisan tinker
```

Kemudian di Tinker:
```php
// Cek semua user
User::all(['id', 'name', 'email', 'username', 'role']);

// Perbaiki role user (contoh: user ID 1)
$user = User::find(1);
$user->role = 'admin'; // atau 'santri'
$user->save();

// Normalisasi semua role
User::all()->each(function($user) {
    $user->role = strtolower(trim($user->role ?? ''));
    $user->save();
});
```

### Langkah 6: Restart Server
1. Stop server (`Ctrl + C` di terminal yang menjalankan `php artisan serve`)
2. Start ulang:
```bash
php artisan serve
```

### Langkah 7: Akses dengan Browser Baru/Incognito
1. Buka **Incognito/Private Window** (`Ctrl + Shift + N` di Chrome/Edge)
2. Atau gunakan browser lain
3. Akses: `http://127.0.0.1:8000`

## 🔍 Verifikasi Perbaikan

Setelah semua langkah di atas, coba akses:

1. **`http://127.0.0.1:8000`** atau **`http://127.0.0.1:8000/login`**
   - ✅ Harus tampil form login (jika belum login)
   - ❌ Jangan sampai redirect loop

2. **Login sebagai Admin:**
   - Email: `admin@pondok.com`
   - Password: `admin123`
   - ✅ Harus redirect ke `/admin/dashboard`

3. **Login sebagai Santri:**
   - Username: (sesuai data di database)
   - Password: (tanggal lahir format YYYY-MM-DD)
   - ✅ Harus redirect ke `/santri/dashboard`

## 🛠️ Perbaikan yang Telah Dilakukan di Code

### 1. Middleware `RedirectIfAuthenticated`
- ✅ Menambahkan pengecekan untuk mencegah redirect loop
- ✅ Jika user sudah di dashboard, tidak akan redirect lagi
- ✅ Logout dan clear session jika role tidak valid

### 2. Middleware `EnsureUserRole`
- ✅ Normalisasi role (lowercase, trim whitespace)
- ✅ Mencegah redirect loop dengan mengecek current route
- ✅ Abort 403 jika sudah di dashboard yang benar tapi role tidak sesuai

### 3. AuthController
- ✅ Validasi role setelah login santri
- ✅ Normalisasi role sebelum redirect
- ✅ Error handling yang lebih baik

## 📝 Default Credentials

**Admin:**
- Email: `admin@pondok.com`
- Password: `admin123`

**Santri:**
- Username: (sesuai data di database, biasanya nama atau NIS)
- Password: (tanggal lahir format YYYY-MM-DD, contoh: `2005-01-15`)

## ⚠️ Jika Masih Terjadi Masalah

### Cek Role User di Database:
```bash
php artisan tinker
```
```php
// Lihat semua user dan role mereka
User::all(['id', 'name', 'email', 'username', 'role'])->toArray();
```

### Pastikan Role Valid:
- Role harus: `'admin'` atau `'santri'` (lowercase, tanpa spasi)
- Jangan: `'Admin'`, `'ADMIN'`, `' admin '`, atau `null`

### Cek Session:
```bash
# Lihat isi folder session
ls storage/framework/sessions/

# Hapus semua session
rm -rf storage/framework/sessions/*
# atau di Windows:
Remove-Item "storage\framework\sessions\*" -Exclude ".gitignore" -Force
```

### Cek Log:
```bash
# Lihat log error
tail -f storage/logs/laravel.log
```

## 📚 File Dokumentasi Lainnya

- `PERBAIKAN_REDIRECT_LOOP.md` - Dokumentasi teknis lengkap
- `FIX_REDIRECT_LOOP.md` - Solusi sebelumnya
- `FIX_AUTO_REDIRECT.md` - Perbaikan auto redirect
- `ALUR_APLIKASI.md` - Alur aplikasi

## ✅ Checklist Perbaikan

- [ ] Hapus cookie browser untuk 127.0.0.1:8000
- [ ] Clear browser cache
- [ ] Hapus semua file session
- [ ] Clear cache Laravel (config, route, view)
- [ ] Perbaiki role user di database
- [ ] Restart server
- [ ] Test dengan browser incognito/baru
- [ ] Verifikasi login admin berhasil
- [ ] Verifikasi login santri berhasil
- [ ] Verifikasi tidak ada redirect loop

Setelah semua checklist selesai, aplikasi seharusnya sudah berfungsi dengan baik!

