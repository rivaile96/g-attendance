<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 12px; 
            color: #333;
        }
        .page-break {
            page-break-after: always;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        h1 { 
            text-align: center; 
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Laporan Absensi Karyawan</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Total Jam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $attendance->user->name ?? 'N/A' }}</td>
                    <td>{{ $attendance->check_in->translatedFormat('d F Y') }}</td>
                    <td>{{ $attendance->check_in->translatedFormat('H:i:s') }}</td>
                    <td>{{ $attendance->check_out ? $attendance->check_out->translatedFormat('H:i:s') : 'Belum Absen Pulang' }}</td>
                    <td>
                        @if ($attendance->check_out)
                            {{ $attendance->check_in->diff($attendance->check_out)->format('%H jam %i menit') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $attendance->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>