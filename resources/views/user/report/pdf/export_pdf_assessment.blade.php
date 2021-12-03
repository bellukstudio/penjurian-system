<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export Data</title>
    <style>
        #table-custom {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #table-custom td,
        #table-custom th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #table-custom tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #table-custom tr:hover {
            background-color: #ddd;
        }

        #table-custom th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #000000;
            color: white;
        }

    </style>
</head>

<body>
    <center>
        <h1>Rekapan Data Penilaian</h1>

    </center>
    <table id="table-custom">
        <tr>
            <th>#</th>
            <th>Nama Acara</th>
            <th>Nama Lomba</th>
            <th>Aspek Penilaian</th>
            <th>Nama Peserta</th>
            <th>Nama Juri</th>
            <th>Total Nilai</th>
        </tr>
        @forelse ($data as $index => $item)
            <tr>
                <td class="border px-6 py-4">{{ $index + 1 }}</td>
                <td class="border px-6 py-4">{{ $item->name_event }}</td>
                <td class="border px-6 py-4">{{ $item->name_contest }}</td>
                <td class="border px-6 py-4">{{ $item->aspek }}</td>
                <td class="border px-6 py-4">{{ $item->name_participants }}</td>
                <td class="border px-6 py-4">{{ $item->user_name }}</td>
                <td class="border px-6 py-4">{{ $item->total_score }}</td>
            </tr>
        @empty
            <td colspan="5" class="border px-6 py-4">Tidak ada data</td>
        @endforelse

    </table>

</body>

</html>
