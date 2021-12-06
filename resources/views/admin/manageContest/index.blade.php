@extends('admin.dashboard')
@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ Breadcrumbs::render('dataContest') }}</h1>
            <a href="{{ route('manageContests.create') }}" class="btn btn-primary">Tambah Data</a>
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('contestFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('contestFailed') }}
            </div>
        @endif
        <div class="card-body mb-5">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <th>#</th>
                        <th>Nama Acara</th>
                        <th>Nama Lomba</th>
                        <th>Jenis Lomba (Kelompok / Individu)</th>
                        <th>Aspek Penilaian</th>
                        <th>Aksi</th>
                        <th>Lihat Peserta</th>
                        <th>Lihat Juri</th>
                    </thead>
                    <tbody>
                        @forelse ($contest as $index => $item)
                            <tr>
                                <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                <td class="border px-6 py-4">{{ $item->acara->name }}</td>
                                <td class="border px-6 py-4">{{ $item->name }}</td>
                                <td class="border px-6 py-4">{{ $item->type }}</td>
                                <td class="border px-6 py-4">{{ $item->assessment_aspect }}</td>
                                <td class="border px-6 py-4">
                                    <a href="{{ route('manageContests.edit', $item->id) }}" style="text-decoration:none;"
                                        class="btn btn-outline-warning">
                                        Edit
                                    </a>
                                    <br>
                                    <br>
                                    <form action="{{ route('manageContests.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <br>
                                    <a href="{{ route('manageParticipants.indexParticipants', $item->id) }}"
                                        class="btn btn-outline-primary">Lihat Peserta</a>
                                </td>
                                <td>
                                    <br>
                                    <a href="{{ route('manageJury.showJuryInContest', $item->id) }}"
                                        class="btn btn-outline-info">Lihat Juri</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="border px-6 py-4 text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
