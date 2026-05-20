<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #212842;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .periode {
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary td {
            padding: 10px;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #212842;
            color: white;
            padding: 8px;
            border: 1px solid #212842;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>

    <h1>Laporan Rekapitulasi Admin</h1>

    <div class="periode">
        Periode:
        {{ \Carbon\Carbon::parse($periodeAwal)->format('d-m-Y') }}
        sampai
        {{ \Carbon\Carbon::parse($periodeAkhir)->format('d-m-Y') }}

        @if ($rombel)
            | Rombel: {{ ucwords(str_replace('-', ' ', $rombel)) }}
        @else
            | Semua Rombel
        @endif
    </div>

    <table class="summary">
        <tr>
            <td>Kas Masuk: Rp {{ number_format($kasMasuk, 0, ',', '.') }}</td>
            <td>Kas Keluar: Rp {{ number_format($kasKeluar, 0, ',', '.') }}</td>
            <td>Saldo: Rp {{ number_format($saldo, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Rombel</th>
                <th>Keterangan</th>
                <th>Kas Masuk</th>
                <th>Kas Keluar</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($rekapitulasi as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                    <td>{{ ucwords(str_replace('-', ' ', $item['rombel'])) }}</td>
                    <td>{{ $item['keterangan'] }}</td>
                    <td class="right">Rp {{ number_format($item['kas_masuk'], 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($item['kas_keluar'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">
                        Belum ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
