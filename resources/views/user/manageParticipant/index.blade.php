@extends('user.dashboard')
@section('content')

    <div class="container">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('participantFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('participantFailed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body mb-5">
            <h5 class="container-sm text-center mb-5">
                {{ Breadcrumbs::render('dataEventsUser.showContest.participant', $contest, $event) }}
            </h5>
            <br>
            <a href="{{ route('manageParticipant.saveParticipant', [$contest->id, $event->id]) }}"
                class="btn btn-primary mb-5">Tambah Peserta Baru </a>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <th>#</th>
                        <th>
                            @if ($contest->type == 'KELOMPOK')
                                Nama Perwakilan Kelompok
                            @else
                                Nama
                            @endif
                        </th>
                        <th>Nama Acara</th>
                        <th>Jenis Kelamin</th>
                        <th>Nomor Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($participant as $index => $item)
                            <tr>
                                <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                <td class="border px-6 py-4">{{ $item->name }}</td>
                                <td class="border px-6 py-4">{{ $item->event->name }}</td>
                                <td class="border px-6 py-4">
                                    @if ($item->gender == 'L')
                                        Laki - Laki
                                    @else
                                        Perempuan
                                    @endif
                                </td>
                                <td class="border px-6 py-4">{{ $item->phone }}</td>
                                <td class="border px-6 py-4">{{ $item->address }}</td>
                                <td class="border px-6 py-4">
                                    <a href="{{ route('manageParticipant.editParticipant', [$item->id, $contest->id_event]) }}"
                                        style="text-decoration:none;">
                                        <i class="fas fa-fw fa-edit"></i>
                                    </a>
                                    <form action="{{ route('manageParticipant.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn bg-transparent">
                                            <i class="fas fa-fw fa-trash text-danger"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="border px-6 py-4">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
