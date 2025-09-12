<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'location_id',
        'check_in',
        'check_out',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_type',
    ];

    /**
     * The attributes that should be cast to native types.
     * Ini akan memastikan kolom 'check_in' dan 'check_out' selalu
     * dianggap sebagai objek Tanggal (Carbon), bukan string biasa.
     *
     * @var array
     */
    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    /**
     * Mendefinisikan relasi bahwa satu catatan Absensi pasti dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi bahwa satu catatan Absensi pasti merujuk ke satu Lokasi.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
