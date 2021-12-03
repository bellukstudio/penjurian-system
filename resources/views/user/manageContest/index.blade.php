@extends('user.dashboard')
@section('content')

    <div class="container">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('raceFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('raceFailed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body mb-5">
            <h3 class="text-center h3">Acara {{ $event->name }}</h3><br>
            <a href="{{ route('manageContest.saveContest', $event->id) }}" class="btn btn-primary mb-5">Tambah Lomba Baru</a>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <th>#</th>
                        <th>Nama Lomba</th>
                        <th>Jenis Lomba (Kelompok / Individu)</th>
                        <th>Nama Acara</th>
                        <th>Aspek Penilaian</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($contest as $index => $item)
                            <tr>
                                <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                <td class="border px-6 py-4">{{ $item->name }}</td>
                                <td class="border px-6 py-4">{{ $item->type }}</td>
                                <td class="border px-6 py-4">{{ $item->acara->name }}</td>
                                <td class="border px-6 py-4">{{ $item->assessment_aspect }}</td>
                                <td class="border px-6 py-4">
                                    <a href="{{ route('manageContest.edit', $item->id) }}" style="text-decoration:none;">
                                        <i class="fas fa-fw fa-edit"></i>
                                    </a>
                                    <form action="{{ route('manageContest.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn bg-transparent">
                                            <i class="fas fa-fw fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('manageParticipant.showParticipant', [$item->id, $event->id]) }}"
                                        style="text-decoration:none;" class="btn btn-light">
                                        Lihat Peserta
                                    </a>
                                    <br>
                                    <a href="{{ route('manageJuri.show', $item->id) }}" style="text-decoration:none;"
                                        class="btn btn-outline-info mt-2">
                                        Lihat Juri
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border px-6 py-4">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <br><br><br>
    </div>
@endsection
