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
        <h2>LAPORAN ACARA</h2>
    </center>
    <div style="overflow-x:auto;">
        <table border="1">
            <tr>
                <th>#</th>
                <th>Nama User</th>
                <th>Nama Acara</th>
                <th>Penanggung Jawab</th>
                <th>Alamat Acara</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Berakhir</th>
            </tr>
            @forelse ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user_name }}</td>
                    <td>{{ $item->name_event }}</td>
                    <td>{{ $item->pr }}</td>
                    <td>{{ $item->address_event }}</td>
                    <td>{{ $item->start_event }}</td>
                    <td>{{ $item->end_event }}</td>
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
