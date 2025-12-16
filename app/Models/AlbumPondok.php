<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumPondok extends Model
{
    protected $table = 'album_pondok';

    protected $fillable = [
        'judul',
        'deskripsi',
        'foto',
        'kategori',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope untuk foto aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk kategori tertentu
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Get kategori options
     */
    public static function getKategoriOptions()
    {
        return [
            'umum' => 'Umum',
            'belajar' => 'Kegiatan Belajar',
            'ngaji' => 'Kegiatan Ngaji',
            'olahraga' => 'Kegiatan Olahraga',
            'keagamaan' => 'Kegiatan Keagamaan',
            'sosial' => 'Kegiatan Sosial',
            'acara' => 'Acara Pondok',
        ];
    }

    /**
     * Relasi ke AlbumFoto
     */
    public function fotos()
    {
        return $this->hasMany(AlbumFoto::class)->orderBy('urutan')->orderBy('created_at');
    }

    /**
     * Foto cover/profil album
     */
    public function coverFoto()
    {
        return $this->hasOne(AlbumFoto::class)->where('is_cover', true);
    }

    /**
     * Get foto cover atau foto pertama
     */
    public function getFotoUrlAttribute()
    {
        // Jika ada foto cover, gunakan itu
        $cover = $this->coverFoto;
        if ($cover) {
            return $cover->foto;
        }
        
        // Jika tidak ada cover, gunakan foto pertama
        $firstFoto = $this->fotos()->first();
        if ($firstFoto) {
            return $firstFoto->foto;
        }
        
        // Fallback ke foto lama jika ada
        return $this->attributes['foto'] ?? null;
    }
}

