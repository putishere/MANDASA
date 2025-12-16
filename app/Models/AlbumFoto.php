<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumFoto extends Model
{
    protected $table = 'album_fotos';

    protected $fillable = [
        'album_pondok_id',
        'foto',
        'judul',
        'deskripsi',
        'urutan',
        'is_cover',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Relasi ke AlbumPondok
     */
    public function albumPondok()
    {
        return $this->belongsTo(AlbumPondok::class);
    }
}

