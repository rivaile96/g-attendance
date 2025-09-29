<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeEvent extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Mendefinisikan relasi bahwa satu Event Lembur memiliki BANYAK penugasan (assignments).
     * INI YANG MEMPERBAIKI ERROR.
     */
    public function assignments()
    {
        return $this->hasMany(OvertimeAssignment::class);
    }
    
    /**
     * Relasi ke user yang membuat event.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}