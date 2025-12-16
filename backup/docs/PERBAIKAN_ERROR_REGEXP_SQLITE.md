# 🔧 PERBAIKAN: Error REGEXP di SQLite

## 📋 Masalah

**Error:**
```
SQLSTATE[HY000]: General error: 1 no such function: REGEXP
```

**Penyebab:**
- Query menggunakan fungsi `REGEXP` yang tidak didukung oleh SQLite
- Fungsi `REGEXP` hanya tersedia di MySQL/MariaDB
- Aplikasi menggunakan SQLite sebagai database default

**Lokasi Error:**
- File: `app/Http/Controllers/SantriController.php`
- Method: `index()`
- Line: 44-54

## ✅ Perbaikan yang Dilakukan

### Sebelum (Tidak Kompatibel dengan SQLite):
```php
$santri = $query->orderByRaw('
    CASE 
        WHEN santri_detail.nis IS NULL OR santri_detail.nis = "" THEN 1 
        ELSE 0 
    END ASC,
    CASE 
        WHEN santri_detail.nis REGEXP "^[0-9]+$" THEN CAST(santri_detail.nis AS UNSIGNED)
        ELSE 999999999
    END ASC,
    santri_detail.nis ASC
')->paginate(10)->withQueryString();
```

### Sesudah (Kompatibel dengan SQLite dan MySQL):
```php
$dbDriver = \DB::connection()->getDriverName();

if ($dbDriver === 'sqlite') {
    // Query untuk SQLite (tidak support REGEXP)
    $santri = $query->orderByRaw('
        CASE 
            WHEN santri_detail.nis IS NULL OR santri_detail.nis = "" THEN 1 
            ELSE 0 
        END ASC,
        santri_detail.nis ASC
    ')->paginate(10)->withQueryString();
} else {
    // Query untuk MySQL/MariaDB (support REGEXP)
    $santri = $query->orderByRaw('
        CASE 
            WHEN santri_detail.nis IS NULL OR santri_detail.nis = "" THEN 1 
            ELSE 0 
        END ASC,
        CASE 
            WHEN santri_detail.nis REGEXP "^[0-9]+$" THEN CAST(santri_detail.nis AS UNSIGNED)
            ELSE 999999999
        END ASC,
        santri_detail.nis ASC
    ')->paginate(10)->withQueryString();
}
```

## 🎯 Penjelasan

### Perbedaan SQLite dan MySQL:

| Fitur | SQLite | MySQL/MariaDB |
|-------|--------|---------------|
| REGEXP | ❌ Tidak didukung | ✅ Didukung |
| GLOB | ✅ Didukung (pattern matching) | ❌ Tidak didukung |
| CAST | ✅ Didukung | ✅ Didukung |
| CASE WHEN | ✅ Didukung | ✅ Didukung |

### Solusi:

1. **Deteksi Driver Database:**
   - Menggunakan `\DB::connection()->getDriverName()` untuk mengetahui driver yang digunakan

2. **Query Berbeda:**
   - **SQLite:** Menggunakan query sederhana tanpa REGEXP
   - **MySQL:** Menggunakan query dengan REGEXP untuk natural sorting numerik

3. **Hasil:**
   - ✅ Kompatibel dengan SQLite
   - ✅ Kompatibel dengan MySQL/MariaDB
   - ✅ Sorting tetap berfungsi (meskipun natural sorting numerik hanya di MySQL)

## ✅ Status: **DIPERBAIKI**

Query sekarang kompatibel dengan kedua database driver.

## 📝 Catatan

Jika ingin natural sorting numerik di SQLite juga, bisa menggunakan:
- Sorting di PHP setelah mengambil data (tapi akan mempengaruhi pagination)
- Atau gunakan MySQL/MariaDB untuk production

---

**Dibuat oleh:** AI Assistant  
**Tanggal:** $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")

