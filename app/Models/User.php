<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'division_id',
        'profile_photo_path',
        'dashboard_layout',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Mendefinisikan relasi: Satu User PASTI MILIK satu Divisi.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Mendefinisikan relasi: Satu User punya BANYAK Absensi.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Mendefinisikan relasi: Satu User punya SATU Profil.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Mendefinisikan relasi: Satu User punya SATU set Data Pekerjaan.
     */
    public function employmentData()
    {
        return $this->hasOne(UserEmploymentData::class);
    }
}

