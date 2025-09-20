<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int|null $division_id
 * @property int|null $shift_id
 * @property string|null $profile_photo_path
 * @property bool $is_admin
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Division|null $division
 * @property-read \App\Models\Shift|null $shift
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attendance[] $attendances
 * @property-read \App\Models\UserProfile|null $profile
 * @property-read \App\Models\UserEmploymentData|null $employmentData
 */
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
        'shift_id', // <-- DITAMBAHKAN
        'profile_photo_path',
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
        'is_admin' => 'boolean',
    ];

    /**
     * Mendefinisikan relasi: Satu User PASTI MILIK satu Divisi.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Mendefinisikan relasi: Satu User PASTI MILIK satu Shift.
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
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

