<?php

namespace App\Exports;

use App\Models\OvertimeLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Carbon;

class OvertimeExport implements FromCollection, WithHeadings, WithMapping
{
    protected $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->logs;
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
            'Jam Mulai',
            'Jam Selesai',
            'Durasi (Menit)',
            'Event Lembur',
            'Catatan',
        ];
    }

    /**
     * @param OvertimeLog $log
     * @return array
     */
    public function map($log): array
    {
        $durationInMinutes = Carbon::parse($log->start_time)->diffInMinutes($log->end_time);

        return [
            $log->user_id,
            $log->user->name,
            optional($log->user->division)->name,
            $log->start_time->format('d-m-Y'),
            $log->start_time->format('H:i:s'),
            $log->end_time->format('H:i:s'),
            $durationInMinutes,
            optional($log->overtimeEvent)->name,
            $log->notes,
        ];
    }
}