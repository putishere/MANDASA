<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SantriDetail extends Model
{
    protected $table = 'santri_detail';

    protected $fillable = [
        'user_id',
        'nis',
        'tahun_masuk',
        'alamat_santri',
        'nomor_hp_santri',
        'foto',
        'status_santri',
        'nama_wali',
        'alamat_wali',
        'nomor_hp_wali',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}