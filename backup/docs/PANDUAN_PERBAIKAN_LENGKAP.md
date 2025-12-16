# 🔧 PANDUAN PERBAIKAN LENGKAP - MANAGEMEN DATA SANTRI

## 📋 Daftar Isi
1. [Script Perbaikan Otomatis](#script-perbaikan-otomatis)
2. [Perbaikan Manual](#perbaikan-manual)
3. [Troubleshooting Masalah Umum](#troubleshooting-masalah-umum)
4. [Verifikasi Setelah Perbaikan](#verifikasi-setelah-perbaikan)

---

## 🚀 Script Perbaikan Otomatis

### **Cara 1: Melalui Browser (Paling Mudah)**

1. **Pastikan server Laravel berjalan**
   ```bash
   php artisan serve
   ```

2. **Buka browser dan akses:**
   ```
   http://127.0.0.1:8000/fix-all
   ```
   Atau jika menggunakan Laragon:
   ```
   http://managemen-data-santri.test/fix-all
   ```

3. **Script akan otomatis:**
   - ✅ Membersihkan semua cache (config, route, view, application)
   - ✅ Menghapus file session yang corrupt
   - ✅ Memperbaiki role user di database
   - ✅ Membuat admin default jika tidak ada
   - ✅ Memperbaiki user santri yang bermasalah
   - ✅ Membuat storage link jika belum ada
   - ✅ Memverifikasi koneksi database
   - ✅ Menampilkan kredensial login

4. **Lihat hasil perbaikan** di halaman browser

### **Cara 2: Melalui Command Line**

```powershell
# Gunakan PHP dari Laragon
C:\laragon\bin\php\php-8.2.x\php.exe fix_semua_masalah.php
```

Atau jika PHP sudah di PATH:
```bash
php fix_semua_masalah.php
```

---

## 🔨 Perbaikan Manual

### **1. Clear Cache dan Session**

```bash
# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Hapus session files (PowerShell)
Remove-Item "storage\framework\sessions\*" -Exclude ".gitignore" -Force
```

### **2. Perbaiki Role User**

**Menggunakan Tinker:**
```bash
php artisan tinker
```

```php
// Normalisasi semua role
use App\Models\User;

User::all()->each(function($user) {
    $userRole = strtolower(trim($user->role ?? ''));
    
    // Jika role kosong atau tidak valid
    if (empty($userRole) || !in_array($userRole, ['admin', 'santri'])) {
        if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL) && !$user->username) {
            $userRole = 'admin';
        } elseif ($user->username) {
            $userRole = 'santri';
        } else {
            $userRole = 'admin'; // default
        }
    }
    
    $user->role = $userRole;
    $user->save();
    echo "User {$user->id}: role = {$userRole}\n";
});
```

### **3. Buat Admin Default**

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$admin = User::firstOrCreate(
    ['email' => 'admin@pondok.test'],
    [
        'name' => 'Admin Pondok',
        'username' => 'admin',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
    ]
);

echo "Admin: {$admin->email} / admin123\n";
```

### **4. Perbaiki User Santri**

```php
use App\Models\User;
use App\Models\SantriDetail;
use Illuminate\Support\Facades\Hash;

$santris = User::where('role', 'santri')->get();

foreach ($santris as $santri) {
    // Pastikan username tidak kosong
    if (empty($santri->username)) {
        $santri->username = 'santri_' . $santri->id;
    }
    
    // Pastikan tanggal lahir ada
    if (!$santri->tanggal_lahir) {
        $santri->tanggal_lahir = '2005-01-01';
    }
    
    // Pastikan password sesuai dengan tanggal lahir
    $tanggalLahirFormatted = $santri->tanggal_lahir->format('Y-m-d');
    if (!Hash::check($tanggalLahirFormatted, $santri->password)) {
        $santri->password = Hash::make($tanggalLahirFormatted);
    }
    
    // Pastikan memiliki santriDetail
    if (!$santri->santriDetail) {
        SantriDetail::create([
            'user_id' => $santri->id,
            'nis' => $santri->username,
            'alamat_santri' => 'Belum diisi',
            'nama_wali' => 'Belum diisi',
            'alamat_wali' => 'Belum diisi',
            'status_santri' => 'aktif',
        ]);
    }
    
    $santri->save();
    echo "Santri {$santri->username} diperbaiki\n";
}
```

### **5. Buat Storage Link**

```bash
php artisan storage:link
```

---

## 🔍 Troubleshooting Masalah Umum

### **Masalah 1: Login Tidak Bisa**

**Gejala:**
- Error "Username atau email tidak ditemukan"
- Error "Password salah"

**Solusi:**
1. Jalankan script perbaikan: `http://127.0.0.1:8000/fix-all`
2. Atau perbaiki manual user yang bermasalah
3. Pastikan password sesuai dengan tanggal lahir (format: YYYY-MM-DD)

### **Masalah 2: Redirect Loop (ERR_TOO_MANY_REDIRECTS)**

**Gejala:**
- Browser terus redirect tanpa henti
- Error "ERR_TOO_MANY_REDIRECTS"

**Solusi:**
1. **Hapus cookie browser:**
   - Buka DevTools (F12)
   - Tab Application → Cookies → `http://127.0.0.1:8000`
   - Clear All

2. **Clear session:**
   ```powershell
   Remove-Item "storage\framework\sessions\*" -Exclude ".gitignore" -Force
   ```

3. **Perbaiki role user** (lihat bagian Perbaikan Manual #2)

4. **Restart server:**
   ```bash
   # Stop server (Ctrl + C)
   php artisan serve
   ```

5. **Gunakan browser incognito/private window**

### **Masalah 3: Error 403 (Akses Ditolak)**

**Gejala:**
- Error "AKSES DITOLAK. ROLE TIDAK VALID UNTUK HALAMAN INI."

**Solusi:**
1. Perbaiki role user di database (lihat bagian Perbaikan Manual #2)
2. Pastikan role adalah `admin` atau `santri` (lowercase, tanpa spasi)
3. Clear cache dan session
4. Restart server

### **Masalah 4: Error 419 (Page Expired)**

**Gejala:**
- Error "419 | Page Expired" setelah submit form

**Solusi:**
1. **Hapus cookie browser** (lihat Masalah 2)
2. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
3. **Refresh halaman** dengan Ctrl + F5
4. **Pastikan CSRF token ter-update** sebelum submit form

### **Masalah 5: Database Connection Error**

**Gejala:**
- Error "SQLSTATE[HY000] [2002] No connection could be made"
- Error "Database tidak ditemukan"

**Solusi:**
1. **Cek file `.env`:**
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=C:\laragon\www\Managemen Data Santri\database\database.sqlite
   ```
   Atau untuk MySQL:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=managemen_data_santri
   DB_USERNAME=root
   DB_PASSWORD=
   ```

2. **Pastikan database file ada** (untuk SQLite):
   ```bash
   # Buat file database.sqlite jika belum ada
   touch database/database.sqlite
   ```

3. **Jalankan migration:**
   ```bash
   php artisan migrate
   ```

4. **Jalankan seeder:**
   ```bash
   php artisan db:seed
   ```

### **Masalah 6: Storage Link Tidak Ada**

**Gejala:**
- Foto tidak bisa diupload atau ditampilkan
- Error "File not found"

**Solusi:**
```bash
php artisan storage:link
```

Atau manual (Windows):
```powershell
mklink /D "public\storage" "storage\app\public"
```

---

## ✅ Verifikasi Setelah Perbaikan

### **1. Verifikasi Database**

```bash
php artisan tinker
```

```php
// Cek admin
$admin = \App\Models\User::where('role', 'admin')->first();
echo "Admin: {$admin->email} / admin123\n";

// Cek santri
$santris = \App\Models\User::where('role', 'santri')->get();
echo "Total santri: {$santris->count()}\n";

// Cek semua user
\App\Models\User::all()->each(function($u) {
    echo "ID: {$u->id}, Name: {$u->name}, Role: {$u->role}\n";
});
```

### **2. Test Login**

1. **Buka browser:** `http://127.0.0.1:8000`
2. **Login sebagai Admin:**
   - Email: `admin@pondok.test`
   - Password: `admin123`
   - Harus redirect ke `/admin/dashboard`

3. **Logout dan login sebagai Santri:**
   - Username: (sesuai data santri)
   - Password: (tanggal lahir format YYYY-MM-DD)
   - Harus redirect ke `/santri/dashboard`

### **3. Test Fitur**

**Admin:**
- ✅ Dashboard admin dapat diakses
- ✅ CRUD Santri berfungsi
- ✅ Profil Pondok dapat diedit
- ✅ Info Aplikasi dapat diedit
- ✅ Album Pondok dapat dikelola

**Santri:**
- ✅ Dashboard santri dapat diakses
- ✅ Profil Santri dapat dilihat
- ✅ Profil Pondok dapat dilihat
- ✅ Album Pondok dapat dilihat

---

## 📝 Checklist Setelah Perbaikan

- [ ] Script perbaikan berhasil dijalankan tanpa error
- [ ] Cache dan session sudah dibersihkan
- [ ] Role user sudah dinormalisasi (admin/santri)
- [ ] Admin default sudah dibuat/ada
- [ ] User santri sudah diperbaiki
- [ ] Storage link sudah dibuat
- [ ] Database connection OK
- [ ] Login admin berhasil
- [ ] Login santri berhasil
- [ ] Tidak ada redirect loop
- [ ] Tidak ada error 403
- [ ] Tidak ada error 419
- [ ] Semua fitur berfungsi

---

## 🎯 Kredensial Default

### **Admin**
- **Email:** `admin@pondok.test`
- **Password:** `admin123`

### **Santri**
- **Username:** Sesuai data santri yang dibuat
- **Password:** Tanggal lahir format `YYYY-MM-DD` (contoh: `2005-09-14`)

---

## ⚠️ Catatan Penting

1. **Hapus route perbaikan setelah digunakan** untuk keamanan:
   - Hapus route `/fix-all` dari `routes/web.php`
   - Hapus route `/fix-login-saput` dari `routes/web.php`

2. **Backup database** sebelum menjalankan script perbaikan

3. **Gunakan browser incognito** untuk testing yang bersih

4. **Clear browser cache** jika masih ada masalah

---

## 📞 Bantuan Tambahan

Jika masih ada masalah setelah menjalankan semua langkah di atas:

1. Cek file log: `storage/logs/laravel.log`
2. Aktifkan debug mode di `.env`:
   ```env
   APP_DEBUG=true
   ```
3. Lihat dokumentasi troubleshooting:
   - `TROUBLESHOOTING_403.md`
   - `PERBAIKAN_REDIRECT_LOOP.md`
   - `SOLUSI_ERROR_419_FINAL.md`

---

**Selamat menggunakan aplikasi!** 🎉

