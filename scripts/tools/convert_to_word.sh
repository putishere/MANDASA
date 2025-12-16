#!/bin/bash

# Script untuk konversi Markdown ke Word menggunakan Pandoc
# Pastikan Pandoc sudah terinstall

echo "=========================================="
echo "  Konversi Markdown ke Word"
echo "  Skripsi/Tugas Akhir"
echo "=========================================="
echo ""

# Cek apakah Pandoc terinstall
if ! command -v pandoc &> /dev/null; then
    echo "[ERROR] Pandoc tidak ditemukan!"
    echo ""
    echo "Silakan install Pandoc terlebih dahulu:"
    echo "  Mac: brew install pandoc"
    echo "  Linux: sudo apt-get install pandoc"
    echo "  Windows: Download dari https://pandoc.org/installing.html"
    echo ""
    exit 1
fi

echo "[INFO] Pandoc ditemukan!"
echo ""

# Input file
read -p "Masukkan nama file markdown (contoh: BAB_2_LANDASAN_TEORI.md): " input_file

if [ ! -f "$input_file" ]; then
    echo "[ERROR] File tidak ditemukan: $input_file"
    exit 1
fi

# Output file
output_file="${input_file%.md}.docx"

echo "[INFO] Mengkonversi $input_file ke $output_file..."
echo ""

# Konversi dengan opsi lengkap
if [ -f "template.docx" ]; then
    pandoc "$input_file" -o "$output_file" \
        --toc \
        --number-sections \
        --highlight-style=tango \
        --reference-doc=template.docx
else
    echo "[WARNING] Template tidak ditemukan, menggunakan default..."
    pandoc "$input_file" -o "$output_file" \
        --toc \
        --number-sections \
        --highlight-style=tango
fi

if [ $? -eq 0 ]; then
    echo "[SUCCESS] File berhasil dikonversi: $output_file"
    echo ""
    echo "[INFO] File Word sudah siap untuk diedit!"
    echo "[INFO] Jangan lupa untuk:"
    echo "  - Format font menjadi Times New Roman 12pt"
    echo "  - Set margin sesuai ketentuan kampus"
    echo "  - Format tabel dan gambar sesuai template"
    echo "  - Periksa referensi dan daftar pustaka"
else
    echo "[ERROR] Gagal mengkonversi file!"
    echo ""
    echo "[TIPS] Pastikan:"
    echo "  - File markdown valid"
    echo "  - Tidak ada karakter khusus yang tidak didukung"
    echo "  - Pandoc versi terbaru"
fi

echo ""

