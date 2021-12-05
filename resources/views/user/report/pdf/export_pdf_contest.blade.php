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
        <h2>LAPORAN LOMBA</h2>
        <h3>ACARA {{ strtoupper($nameEvent) }}</h3>
    </center>
    <div style="overflow-x:auto;">
        <table border="1">
            <tr>
                <th>#</th>
                <th>Nama Lomba</th>
                <th>Jenis Lomba (Kelompok / Individu)</th>
                <th>Nama Acara</th>
                <th>Aspek Penilaian</th>
            </tr>
            @forelse ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->contest_name }}</td>
                    <td>{{ $item->type_contest }}</td>
                    <td>{{ $item->event_name }}</td>
                    <td>{{ $item->contest_aspect }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center">Tidak ada data</td>
                </tr>
            @endforelse

        </table>
    </div>

</body>

</html>
