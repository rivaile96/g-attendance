<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Carbon;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->attendances;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Karyawan',
            'Nama Karyawan',
            'Divisi',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Total Jam (Menit)',
            'Status',
            'Lokasi',
        ];
    }

    /**
     * @param Attendance $attendance
     * @return array
     */
    public function map($attendance): array
    {
        $durationInMinutes = null;
        if ($attendance->check_out) {
            $durationInMinutes = Carbon::parse($attendance->check_in)->diffInMinutes($attendance->check_out);
        }

        return [
            $attendance->user_id,
            $attendance->user->name,
            optional($attendance->user->division)->name,
            $attendance->check_in->format('d-m-Y'),
            $attendance->check_in->format('H:i:s'),
            $attendance->check_out ? $attendance->check_out->format('H:i:s') : 'Belum Check-out',
            $durationInMinutes,
            $attendance->status,
            optional($attendance->location)->name,
        ];
    }
}