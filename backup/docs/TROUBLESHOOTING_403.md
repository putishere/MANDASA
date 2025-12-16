# Troubleshooting Error 403 Forbidden pada Login Admin

## Langkah-langkah Perbaikan:

### 1. Pastikan APP_KEY sudah di-set

Jalankan perintah berikut:

```bash
php artisan key:generate
```

### 2. Clear cache dan config

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 3. Pastikan Session Table sudah dibuat

Jika menggunakan `SESSION_DRIVER=database`, pastikan tabel `sessions` sudah dibuat:

```bash
php artisan migrate
```

### 4. Periksa file .env

Pastikan di file `.env`:

-   `APP_KEY` sudah di-set
-   `SESSION_DRIVER` sesuai (file atau database)
-   `APP_URL` sesuai dengan URL aplikasi Anda

### 5. Periksa permission folder storage

Pastikan folder `storage` dan `bootstrap/cache` memiliki permission write:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 6. Periksa browser console

Buka Developer Tools (F12) di browser dan periksa:

-   Apakah ada error di Console?
-   Apakah CSRF token terkirim di Network tab?

### 7. Test dengan curl

Test apakah route POST bekerja:

```bash
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "username=admin@pondok.test&password=admin123&_token=YOUR_TOKEN"
```

**Catatan**: Route login sekarang unified di `/login` (bukan `/login/admin` atau `/login/santri`). Sistem akan otomatis mendeteksi apakah input adalah email (admin) atau username (santri).

### 8. Periksa log Laravel

Cek file `storage/logs/laravel.log` untuk melihat error detail.

### 9. Perbaiki Error ERR_TOO_MANY_REDIRECTS

Jika setelah login muncul error "ERR_TOO_MANY_REDIRECTS" atau "terlalu sering mengalihkan", kemungkinan ada masalah dengan:

1. **Role user di database tidak tepat** (ada spasi, case berbeda, atau karakter aneh)
2. **Redirect loop di middleware**

**Solusi:**

1. Jalankan script untuk cek dan perbaiki role user:
   ```bash
   php check_and_fix_roles.php
   ```

2. Atau perbaiki manual di database:
   ```sql
   -- Cek semua user dan role mereka
   SELECT id, name, username, email, role, LENGTH(role) as role_length
   FROM users;
   
   -- Perbaiki role yang mungkin ada masalah
   UPDATE users SET role = 'santri' WHERE TRIM(LOWER(role)) = 'santri' AND role != 'santri';
   UPDATE users SET role = 'admin' WHERE TRIM(LOWER(role)) = 'admin' AND role != 'admin';
   ```

3. Clear cache dan session:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

4. Hapus cookie browser untuk situs `127.0.0.1:8000` atau gunakan mode incognito/private browsing

5. Coba login lagi
