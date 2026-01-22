<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kegiatan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #000; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header h3 { margin: 5px 0; font-size: 14px; font-weight: normal; }
        .header p { margin: 0; font-size: 12px; font-style: italic; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        
        .footer { margin-top: 30px; text-align: right; font-size: 11px; }
        .print-btn { margin-bottom: 20px; text-align: center; }
        .print-btn button { padding: 10px 20px; background: #0d6efd; color: white; border: none; cursor: pointer; border-radius: 5px; }
        
        @media print { 
            .print-btn { display: none; } 
            @page { size: landscape; margin: 1cm; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="print-btn">
        <button onclick="window.print()">Cetak Halaman Ini</button>
    </div>

    <div class="header">
        <h2>DINAS KESEHATAN</h2>
        <h3>LAPORAN REKAPITULASI AGENDA KEGIATAN</h3>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Hari/Tanggal</th>
                <th style="width: 10%">Waktu</th>
                <th style="width: 25%">Nama Kegiatan</th>
                <th style="width: 20%">Lokasi</th>
                <th style="width: 25%">Keterangan / Peserta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agendas as $index => $agenda)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>{{ $agenda->start_time->isoFormat('dddd, D MMM Y') }}</td>
                <td style="text-align: center">
                    {{ $agenda->start_time->format('H:i') }} <br> s/d <br>
                    {{ $agenda->end_time ? $agenda->end_time->format('H:i') : 'Selesai' }}
                </td>
                <td>
                    <strong>{{ $agenda->title }}</strong>
                    <br>
                    <span style="font-size: 10px; color: #555;">(Input: {{ $agenda->user->name ?? '-' }})</span>
                </td>
                <td>{{ $agenda->location }}</td>
                <td>
                    {{ implode(', ', $agenda->participants ?? []) }}
                    @if($agenda->description)
                        <br><br><em>Catatan: {{ $agenda->description }}</em>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y, H:i') }} WIB</p>
    </div>

</body>
</html>