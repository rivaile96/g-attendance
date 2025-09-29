<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeAssignment extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    public $timestamps = false; // Tabel ini tidak butuh created_at/updated_at

    /**
     * Relasi ke event lembur.
     */
    public function overtimeEvent()
    {
        return $this->belongsTo(OvertimeEvent::class);
    }

    /**
     * Relasi polymorphic ke model lain (bisa Division, User, dll).
     */
    public function assignable()
    {
        return $this->morphTo();
    }
}