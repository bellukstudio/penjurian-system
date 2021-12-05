<!DOCTYPE html>
<html lang="en">

<head>
    <title>Export PDF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #ddd;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(1) {
            background-color: #c9c9c9
        }

    </style>
</head>

<body>
    <center>
        <h2>LAPORAN PENILAIAN</h2>
        <h3>ACARA {{ strtoupper($nameEvent) }}</h3>
        <h3>LOMBA {{ strtoupper($nameContest) }}</h3>
        {{-- <h3>{{ $maxAverage->average }}</h3> --}}
    </center>
    <div style="overflow-x:auto;">
        <table border="1">
            <tr>
                <th>#</th>
                <th>Nama Acara</th>
                <th>Nama Lomba</th>
                <th>Email User</th>
                <th>Aspek Penilaian</th>
                <th>Nama Peserta</th>
                <th>Total Nilai</th>
                <th>Rata Rata</th>
            </tr>
            @forelse ($data as $index => $item)
                <tr>
                    <td >{{ $index + 1 }}</td>
                    <td>{{ $item->name_event }}</td>
                    <td >{{ $item->name_contest }}</td>
                    <td >{{ $item->email_user }}</td>
                    <td>{{ $item->aspek }}d</td>
                    <td>{{ $item->name_participants }}</td>
                    <td>{{ $item->total_score }}</td>
                    <td>{{ $item->average }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="border px-6 py-4 text-center">Tidak ada data</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5" style="text-align: center">Rata rata tertinggi</td>
                <td colspan="2" style="text-align: center">{{ $maxAverage->average }}</td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center">Rata rata terendah</td>
                <td colspan="2" style="text-align: center">{{ $minAverage->average }}</td>
            </tr>

        </table>
    </div>

</body>

</html>
