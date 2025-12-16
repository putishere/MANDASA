# Cara Memperbaiki Masalah Foto Tidak Tampil

## Masalah
Foto yang diupload tidak tampil karena storage link belum dibuat.

## Solusi

Jalankan perintah berikut di terminal:

```bash
php artisan storage:link
```

Perintah ini akan membuat symbolic link dari `public/storage` ke `storage/app/public`, sehingga file yang disimpan di `storage/app/public` dapat diakses melalui URL `public/storage`.

## Setelah menjalankan perintah:

1. Pastikan folder `storage/app/public` ada
2. Pastikan folder `public/storage` terbuat (akan dibuat otomatis oleh perintah)
3. Foto yang sudah diupload akan tampil di halaman

## Catatan

- Jika menggunakan Windows, pastikan menjalankan sebagai Administrator
- Jika masih tidak tampil, pastikan permission folder storage sudah benar:
  ```bash
  chmod -R 775 storage
  chmod -R 775 bootstrap/cache
  ```

