<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $user
 */
class UserProfile extends Model
{
    use HasFactory;

    /**
     * Kolom yang tidak boleh diisi massal.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Casting atribut ke tipe data khusus.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date', // otomatis jadi instance Carbon
    ];

    /**
     * Relasi: Profile -> User (many to one).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
