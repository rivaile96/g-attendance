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
     * Kolom yang bisa diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'division_id',
        'shift_id',
        'profile_photo_path',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_admin'          => 'boolean',
    ];

    /**
     * Relasi: User -> Division (many to one).
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Relasi: User -> Shift (many to one).
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Relasi: User -> Attendance (one to many).
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relasi: User -> Profile (one to one).
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Relasi: User -> EmploymentData (one to one).
     */
    public function employmentData()
    {
        return $this->hasOne(UserEmploymentData::class);
    }
}
