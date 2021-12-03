@extends('user.dashboard')
@section('content')
    <div class="container-fluid">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('eventFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('eventFailed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h2 class="text-center">Data Acara</h2>

        <div class="card-body mb-5">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <th>#</th>
                        <th>Nama Acara</th>
                        <th>Penanggung Jawab</th>
                        <th>Alamat Acara</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Berakhir</th>
                        <th>Token</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($event as $index => $item)
                            <tr>
                                <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                <td class="border px-6 py-4">{{ $item->name }}</td>
                                <td class="border px-6 py-4">{{ $item->name_person_responsible }}</td>
                                <td class="border px-6 py-4">{{ $item->address }}</td>
                                <td class="border px-6 py-4">{{ $item->start_date }}</td>
                                <td class="border px-6 py-4">{{ $item->end_date }}</td>
                                <td class="border px-6 py-4">{{ $item->token }}</td>
                                <td class="border px-6 py-4">
                                    <a href="{{ route('manageEvent.edit', $item->id) }}" style="text-decoration:none;">
                                        <i class="fas fa-fw fa-edit"></i>
                                    </a>
                                    <form action="{{ route('manageEvent.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn bg-transparent">
                                            <i class="fas fa-fw fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('manageContest.show', $item->id) }}" style="text-decoration:none;"
                                        class="btn btn-light">
                                        Lihat Lomba
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <td colspan="8" class="border px-6 py-4">Tidak ada data</td>

                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
