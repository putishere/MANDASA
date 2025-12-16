<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPondok extends Model
{
    protected $table = 'profil_pondok';

    protected $fillable = [
        'nama_pondok',
        'subtitle',
        'tentang',
        'visi',
        'misi',
        'program_unggulan',
        'fasilitas',
        'logo',
    ];

    /**
     * Get single instance (singleton pattern)
     */
    public static function getInstance()
    {
        $instance = self::first();
        if (!$instance) {
            $instance = self::create([
                'nama_pondok' => 'PP HS AL-FAKKAR',
                'subtitle' => '',
                'tentang' => null,
                'visi' => null,
                'misi' => null,
                'program_unggulan' => null,
                'fasilitas' => null,
                'logo' => null,
            ]);
        }
        return $instance;
    }
}

