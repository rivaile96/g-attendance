<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Mendefinisikan relasi bahwa satu Shift bisa dimiliki oleh BANYAK User.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}