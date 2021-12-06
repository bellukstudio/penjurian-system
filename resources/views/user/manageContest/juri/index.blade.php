@extends('user.dashboard')
@section('content')

    <div class="container">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('juryFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('juryFailed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body mb-5">
            <h5 class="container-sm text-center mb-5">{{ Breadcrumbs::render('dataEventsUser.showContest.showJury', $contest,$event) }}</h5>
            <br>
            <a href="{{ route('manageJuri.saveJury', $contest->id) }}" class="btn btn-primary mb-5">Tambah Juri Baru</a>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <th>#</th>
                        <th>Nama Juri</th>
                        <th>Nama Lomba</th>
                        <th>Nama Acara</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($jury as $index => $item)
                            <tr>
                                <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                <td class="border px-6 py-4">{{ $item->user->name }}</td>
                                <td class="border px-6 py-4">{{ $item->contest->name }}</td>
                                <td class="border px-6 py-4">{{ $item->event->name }}</td>
                                <td class="border px-6 py-4">
                                    <a href="{{ route('manageJuri.editJury', $item->contest->id) }}"
                                        style="text-decoration:none;">
                                        <i class="fas fa-fw fa-edit"></i>
                                    </a>
                                    <form action="{{ route('manageJuri.destroyJury', $item->id) }}" method="POST">
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
                                <td colspan="5" class="border px-6 py-4">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
