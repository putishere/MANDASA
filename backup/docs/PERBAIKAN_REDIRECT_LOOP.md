# Perbaikan Redirect Loop (ERR_TOO_MANY_REDIRECTS)

## Masalah
Error `ERR_TOO_MANY_REDIRECTS` terjadi ketika aplikasi terus-menerus melakukan redirect tanpa henti, biasanya karena:
1. Session yang corrupt
2. Role user tidak valid atau null di database
3. Middleware yang saling redirect
4. Cookie browser yang corrupt

## Solusi Lengkap

### Langkah 1: Jalankan Script Perbaikan
Jalankan script perbaikan untuk membersihkan semua masalah:

```bash
php fix_all_issues.php
```

Script ini akan:
- Membersihkan semua file session
- Membersihkan cache aplikasi (config, route, view)
- Memperbaiki role user di database
- Memverifikasi user admin dan santri
- Membuat admin default jika tidak ada

### Langkah 2: Hapus Cookie Browser
**PENTING:** Hapus cookie untuk `127.0.0.1:8000` di browser:

**Chrome/Edge:**
1. Tekan `F12` untuk buka DevTools
2. Buka tab **Application** (Chrome) atau **Storage** (Edge)
3. Di sidebar kiri, klik **Cookies** → `http://127.0.0.1:8000`
4. Klik kanan pada cookie dan pilih **Delete** atau klik tombol **Clear All**
5. Refresh halaman (`Ctrl + F5`)

**Atau gunakan tombol di halaman error:**
- Klik tombol **"Hapus cookie"** (Delete cookie) yang muncul di halaman error

### Langkah 3: Clear Browser Cache
1. Tekan `Ctrl + Shift + Delete`
2. Pilih **Cookies and other site data** dan **Cached images and files**
3. Pilih **All time**
4. Klik **Clear data**

### Langkah 4: Restart Server
```bash
# Stop server (Ctrl + C)
# Start ulang
php artisan serve
```

### Langkah 5: Akses Aplikasi
Buka browser baru atau incognito/private window dan akses:
```
http://127.0.0.1:8000
```

## Perbaikan yang Telah Dilakukan

### 1. Middleware `RedirectIfAuthenticated`
- Menambahkan pengecekan untuk mencegah redirect loop
- Jika user sudah di dashboard, tidak akan redirect lagi
- Logout dan clear session jika role tidak valid

### 2. Middleware `EnsureUserRole`
- Normalisasi role (lowercase, trim whitespace)
- Mencegah redirect loop dengan mengecek current route
- Abort 403 jika sudah di dashboard yang benar tapi role tidak sesuai

### 3. AuthController
- Validasi role setelah login santri
- Normalisasi role sebelum redirect
- Error handling yang lebih baik

## Troubleshooting

### Jika Masih Terjadi Redirect Loop:

1. **Cek Role User di Database:**
```bash
php artisan tinker
```
```php
User::all(['id', 'name', 'email', 'username', 'role'])->toArray();
```

2. **Fix Role Manual:**
```php
// Di tinker
$user = User::find(1);
$user->role = 'admin'; // atau 'santri'
$user->save();
```

3. **Clear Semua Session:**
```bash
php artisan session:clear
# atau
rm -rf storage/framework/sessions/*
```

4. **Clear Semua Cache:**
```bash
php artisan optimize:clear
```

5. **Restart Server:**
```bash
# Stop server
# Hapus file .env jika perlu (backup dulu!)
# Start ulang
php artisan serve
```

## Default Credentials

Setelah menjalankan `fix_all_issues.php`, gunakan credentials berikut:

**Admin:**
- Email: `admin@pondok.com`
- Password: `admin123`

**Santri:**
- Username: (sesuai data di database)
- Password: (tanggal lahir format YYYY-MM-DD, contoh: 2005-01-15)

## Catatan Penting

1. **Jangan akses aplikasi dengan session lama** - selalu clear cookie dan cache setelah menjalankan script perbaikan
2. **Gunakan browser baru atau incognito** untuk testing setelah perbaikan
3. **Pastikan role user selalu lowercase** - 'admin' atau 'santri', bukan 'Admin' atau 'ADMIN'
4. **Jangan ada whitespace di role** - pastikan role di database sudah di-trim

## Verifikasi Perbaikan

Setelah perbaikan, verifikasi dengan:

1. **Akses root URL (`/`):**
   - Jika belum login → tampil form login
   - Jika sudah login → redirect ke dashboard sesuai role

2. **Akses `/login`:**
   - Jika belum login → tampil form login
   - Jika sudah login → redirect ke dashboard sesuai role

3. **Akses `/admin/dashboard`:**
   - Jika login sebagai admin → tampil dashboard admin
   - Jika login sebagai santri → redirect ke `/santri/dashboard`
   - Jika belum login → redirect ke `/login`

4. **Akses `/santri/dashboard`:**
   - Jika login sebagai santri → tampil dashboard santri
   - Jika login sebagai admin → redirect ke `/admin/dashboard`
   - Jika belum login → redirect ke `/login`

Jika semua skenario di atas bekerja dengan benar, maka masalah redirect loop sudah teratasi.

