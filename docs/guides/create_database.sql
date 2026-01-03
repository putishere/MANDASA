-- Script SQL untuk membuat database managemen_data_santri
-- 
-- Cara menggunakan:
-- 1. Buka phpMyAdmin di Laragon: http://localhost/phpmyadmin
-- 2. Pilih tab "SQL"
-- 3. Copy-paste script ini
-- 4. Klik "Go" atau tekan Ctrl+Enter
--
-- Atau gunakan MySQL command line:
-- mysql -u root -p < create_database.sql

-- Hapus database jika sudah ada (HATI-HATI: ini akan menghapus semua data!)
-- DROP DATABASE IF EXISTS managemen_data_santri;

-- Buat database baru
CREATE DATABASE IF NOT EXISTS managemen_data_santri 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Tampilkan konfirmasi
SELECT 'Database managemen_data_santri berhasil dibuat!' AS Status;

-- Gunakan database
USE managemen_data_santri;

-- Tampilkan informasi database
SHOW CREATE DATABASE managemen_data_santri;

