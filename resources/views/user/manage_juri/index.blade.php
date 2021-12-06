@extends('user.dashboard')
@section('content')

    <div class="container">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('juriFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('juriFailed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h5 class="container-sm text-center">{{ Breadcrumbs::render('dataJuriUser') }}</h5>
        <div class="card-body mb-5">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Alamat Email</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($juri as $index => $item)
                            <tr>
                                <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                <td class="border px-6 py-4">{{ $item->name }}</td>
                                <td class="border px-6 py-4">{{ $item->email }}</td>
                                <td class="border px-6 py-4">
                                    <a href="{{ route('manageJuri.edit', $item->id) }}" style="text-decoration:none;">
                                        <i class="fas fa-fw fa-edit"></i>
                                    </a>
                                    <form action="{{ route('manageJuri.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn bg-transparent">
                                            <i class="fas fa-fw fa-trash text-danger"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <td colspan="4" class="border px-6 py-4">Tidak ada data</td>


                        @endforelse
                    </tbody>
                </table>
            </div>
            <br><br><br>
        </div>
    </div>
@endsection
