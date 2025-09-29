<!DOCTYPE html>
<html>
<head>
    <title>Laporan Lembur</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Laporan Rekapitulasi Lembur</h1>
    <table>
        <thead>
            <tr>
                <th>Karyawan</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Durasi</th>
                <th>Event</th>
            </tr>
        </thead>
        <tbody>
            @php $totalMinutes = 0; @endphp
            @foreach ($overtimeLogs as $log)
                <tr>
                    <td>{{ $log->user->name }}</td>
                    <td>{{ $log->start_time->translatedFormat('d M Y') }}</td>
                    <td>{{ $log->start_time->format('H:i') }} - {{ $log->end_time->format('H:i') }}</td>
                    <td>
                        @php
                            $duration = $log->start_time->diffInMinutes($log->end_time);
                            $totalMinutes += $duration;
                            echo floor($duration / 60) . ' jam ' . ($duration % 60) . ' mnt';
                        @endphp
                    </td>
                    <td>{{ optional($log->overtimeEvent)->name }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="total" style="text-align:right;">Total Durasi</td>
                <td colspan="2" class="total">{{ floor($totalMinutes / 60) . ' jam ' . ($totalMinutes % 60) . ' menit' }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>