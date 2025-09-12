<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Properti ini penting agar kita bisa membuat data baru secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'ip_address',
        'latitude',
        'longitude',
        'radius',
    ];

    /**
     * Mendefinisikan relasi bahwa satu Lokasi bisa memiliki banyak catatan absensi.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
