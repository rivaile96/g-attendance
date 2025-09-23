<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // ▼▼▼ TAMBAHKAN BLOK INI ▼▼▼
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date', // Beri tahu Laravel bahwa kolom 'date' adalah tipe tanggal
    ];
    // ▲▲▲ ------------------- ▲▲▲
}