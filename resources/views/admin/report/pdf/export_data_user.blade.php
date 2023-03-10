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
    </style>
</head>

<body>
    <center>
        <h2>LAPORAN DATA USER</h2>
    </center>
    <div style="overflow-x:auto;">
        <table border="1">
            <tr>
                <th>#</th>
                <th>Nama User</th>
                <th>Alamat Email</th>
                <th>Role</th>
            </tr>
            @forelse ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->roles }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center">Tidak ada data</td>
                </tr>
            @endforelse

        </table>
    </div>

</body>

</html>
