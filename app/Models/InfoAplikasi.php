<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoAplikasi extends Model
{
    protected $table = 'info_aplikasi';

    protected $fillable = [
        'judul',
        'tentang',
        'fitur',
        'keamanan',
        'bantuan',
        'versi',
        'framework',
        'database',
    ];

    /**
     * Get single instance (singleton pattern)
     */
    public static function getInstance()
    {
        $instance = self::first();
        if (!$instance) {
            $instance = self::create([
                'judul' => 'Managemen Data Santri',
                'tentang' => null,
                'fitur' => null,
                'keamanan' => null,
                'bantuan' => null,
                'versi' => '1.0.0',
                'framework' => 'Laravel 12',
                'database' => 'MySQL',
            ]);
        }
        return $instance;
    }
}

