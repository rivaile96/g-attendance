<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmploymentData extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'join_date' => 'date',          // <-- TAMBAHKAN INI
        'probation_end_date' => 'date', // <-- TAMBAHKAN INI
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}