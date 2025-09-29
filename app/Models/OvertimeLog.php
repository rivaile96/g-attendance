<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeLog extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     * '[id]' berarti semua kolom boleh diisi kecuali 'id'.
     * INILAH YANG MEMPERBAIKI ERROR.
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
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Relasi ke User yang mengajukan lembur.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Event Lembur terkait.
     */
    public function overtimeEvent()
    {
        return $this->belongsTo(OvertimeEvent::class, 'overtime_event_id');
    }

    /**
     * Relasi ke User yang menyetujui lembur.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}