<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    /**
     * Alias untuk User dengan role santri
     * Model ini digunakan untuk kemudahan query santri
     */
    
    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'tanggal_lahir',
        'role',
    ];

    /**
     * Scope untuk hanya mengambil user dengan role santri
     */
    public function scopeSantri($query)
    {
        return $query->where('role', 'santri');
    }

    /**
     * Relasi ke SantriDetail
     */
    public function detail()
    {
        return $this->hasOne(SantriDetail::class, 'user_id');
    }

    /**
     * Relasi ke SantriDetail (alias untuk detail)
     */
    public function santriDetail()
    {
        return $this->hasOne(SantriDetail::class, 'user_id');
    }
}
