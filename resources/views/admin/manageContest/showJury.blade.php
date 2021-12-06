@extends('admin.dashboard')
@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ Breadcrumbs::render('dataContest.showJury', $contest) }}</h1>

            <a href="{{ route('manageJury.createJuryInContest', [$contest->id, $contest->id_event]) }}"
                class="btn btn-primary">Tambah Juri
                {{ $contest->name }}</a>
        </div>
        <div class="card-body mb-5">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session()->has('juryFailed'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('juryFailed') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <th>#</th>
                        <th>Nama Juri</th>
                        <th>Nama Acara</th>
                        <th>Nama Lomba</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($juri as $index => $item)
                            <tr>
                                <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                <td class="border px-6 py-4">{{ $item->user->name }}</td>
                                <td class="border px-6 py-4">{{ $item->event->name }}</td>
                                <td class="border px-6 py-4">{{ $item->contest->name }}</td>
                                <td class="border px-6 py-4" width="20%">
                                    <form action="" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <td colspan="5" class="border px-6 py-4 text-center">Tidak ada data</td>

                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
