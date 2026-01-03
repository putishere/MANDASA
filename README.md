# 📚 Managemen Data Santri - PP HS AL-FAKKAR

Sistem Informasi Manajemen Data Santri untuk Pondok Pesantren HS AL-FAKKAR.

## 🚀 Teknologi

- **Framework:** Laravel 12.0
- **PHP:** ^8.2
- **Frontend:** TailwindCSS 4.0, Vite
- **Database:** MySQL (production ready)

## 📁 Struktur Folder

```
├── app/                    # Application code
│   ├── Http/
│   │   ├── Controllers/    # Controllers
│   │   └── Middleware/     # Middleware
│   └── Models/             # Eloquent models
│
├── config/                 # Configuration files
├── database/               # Migrations, seeders, factories
├── docs/                   # Dokumentasi
│   ├── deployment/         # Dokumentasi deployment & hosting
│   ├── proposal/          # File proposal & analisis
│   ├── security/          # Dokumentasi keamanan
│   ├── old/               # Dokumentasi lama (archive)
│   └── guides/            # Panduan penggunaan
│
├── public/                 # Public assets (web root)
├── resources/              # Views, CSS, JS
├── routes/                 # Route definitions
├── scripts/                # Utility scripts
│   ├── tools/             # Tools untuk development
│   └── *.php, *.bat, *.ps1 # Script utilities
│
├── storage/                # Storage (logs, cache, uploads)
├── tests/                  # Test files
└── vendor/                 # Composer dependencies
```

## 📖 Dokumentasi

### Deployment & Hosting
- [Checklist Hosting dan Domain](docs/deployment/CHECKLIST_HOSTING_DAN_DOMAIN.md)
- [Laporan Cek Deployment](docs/deployment/LAPORAN_CEK_DEPLOYMENT.md)

### Keamanan
- [Status Keamanan dan Fungsionalitas](docs/security/STATUS_KEAMANAN_DAN_FUNGSIONALITAS.md)
- [Ringkasan Perbaikan Keamanan](docs/security/RINGKASAN_PERBAIKAN_KEAMANAN.md)
- [Hasil Test Setelah Perbaikan](docs/security/HASIL_TEST_SETELAH_PERBAIKAN.md)

### Proposal & Analisis
- Lihat folder [docs/proposal/](docs/proposal/) untuk file proposal lengkap

## 🛠️ Instalasi

### Requirements
- PHP ^8.2
- Composer
- Node.js & NPM
- SQLite atau MySQL

### Setup

1. **Clone repository**
```bash
git clone <repository-url>
cd "Managemen Data Santri"
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Setup environment**
```bash
# Copy template environment
cp ENV_TEMPLATE.txt .env

# Generate application key
php artisan key:generate

# Setup database (MySQL untuk production)
# Lihat docs/guides/MIGRASI_KE_MYSQL.md untuk panduan lengkap
```

4. **Run migrations**
```bash
php artisan migrate
```

5. **Seed database (optional)**
```bash
php artisan db:seed
```

6. **Build assets**
```bash
npm run build
```

7. **Start development server**
```bash
php artisan serve
```

## 🔐 Default Credentials (Development Only)

**Admin:**
- Email: `admin@pondok.test`
- Password: `admin123`

⚠️ **PENTING:** Ganti password default setelah deployment ke production!

## 📝 Environment Variables

Lihat file `ENV_TEMPLATE.txt` untuk daftar lengkap environment variables yang diperlukan.

## 🚀 Deployment

Lihat dokumentasi di [docs/deployment/](docs/deployment/) untuk panduan lengkap deployment.

## 🔒 Keamanan

- ✅ Route debug/test sudah dihapus
- ✅ Hardcoded credentials sudah dihapus
- ✅ Menggunakan environment variables
- ✅ Siap untuk production deployment

Lihat dokumentasi keamanan di [docs/security/](docs/security/).

## 📄 License

MIT License

## 👥 Kontributor

PP HS AL-FAKKAR Development Team

---

**Status:** ✅ Siap untuk deployment

