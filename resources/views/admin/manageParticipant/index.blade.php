@extends('admin.dashboard')
@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                @if (Route::is('manageParticipants.indexParticipants'))
                    {{ Breadcrumbs::render('dataContest.show', $contest) }}
                @else
                    {{ Breadcrumbs::render('dataParticipants') }}
                @endif
            </h1>
            @if (Route::is('manageParticipants.indexParticipants'))
                <a href="{{ route('manageParticipants.createParticipants', $contest->id) }}" class="btn btn-primary">Tambah
                    Data</a>

            @else
                <div></div>
            @endif
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('participantFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('participantFailed') }}
            </div>
        @endif
        <div class="card-body mb-5">

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <th>#</th>
                        <th>
                            Nama Peserta / Nama Perwakilan Peserta
                        </th>
                        <th>Nama Acara</th>
                        <th>Nama Lomba</th>
                        <th>Jenis Kelamin</th>
                        <th>Nomor Telepon</th>
                        <th>Alamat</th>
                        <th>Nomor Urut</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($participant as $index => $item)
                            <tr>
                                <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                <td class="border px-6 py-4">{{ $item->name }}</td>
                                <td class="border px-6 py-4">{{ $item->event->name }}</td>
                                <td class="border px-6 py-4">{{ $item->contest->name }}</td>
                                <td class="border px-6 py-4">
                                    @if ($item->gender == 'L')
                                        Laki - Laki
                                    @else
                                        Perempuan
                                    @endif
                                </td>
                                <td class="border px-6 py-4">{{ $item->phone }}</td>
                                <td class="border px-6 py-4">{{ $item->address }}</td>
                                <td class="border px-6 py-4">{{ $item->serial_number }}</td>
                                <td class="border px-6 py-4">
                                    <a href="{{ route('manageParticipants.edit', $item->id) }}"
                                        style="text-decoration:none;" class="btn btn-outline-warning">
                                        Edit
                                    </a>
                                    <br>
                                    <br>
                                    <form action="{{ route('manageParticipants.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="border px-6 py-4 text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
