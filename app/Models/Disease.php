<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;

    protected $table = 'diseases'; // Nama tabel sesuai dengan migrasi

    protected $fillable = [
        'name',
        'description',
        'recommendation',
    ];

    /**
     * Jika ada relasi dengan model lain, bisa ditambahkan di sini.
     */
}