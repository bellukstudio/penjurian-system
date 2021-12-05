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
        <h2>LAPORAN DATA PESERTA</h2>
        <h3>ACARA {{ strtoupper($nameEvent) }}</h3>
        <h3>LOMBA {{ strtoupper($nameContest) }}</h3>
    </center>
    <div style="overflow-x:auto;">
        <table border="1">
            <tr>
                <th>#</th>
                <th>Nama Peserta</th>
                <th>Jenis Kelamin</th>
                <th>No Telepon</th>
                <th>Alamat</th>
                <th>Nama Acara</th>
                <th>Nama Lomba</th>
            </tr>
            @forelse ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name_participant }}</td>
                    <td>@if ($item->gender_participant == "L")
                        Laki - Laki
                    @else
                        Perempuan
                    @endif</td>
                    <td>{{ $item->phone_participant }}</td>
                    <td>{{ $item->address_participant }}</td>
                    <td>{{ $item->name_event }}</td>
                    <td>{{ $item->name_contest }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center">Tidak ada data</td>
                </tr>
            @endforelse

        </table>
    </div>

</body>

</html>
