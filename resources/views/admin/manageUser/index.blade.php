@extends('admin.dashboard')
@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ Breadcrumbs::render('dataUser'); }}</h1>
            <a href="{{ route('manageUser.create') }}" class="btn btn-primary">Tambah Data</a>
        </div>
        <div class="card-body mb-5">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session()->has('userFailed'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('userFailed') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <th>#</th>
                        <th>Nama User</th>
                        <th>Alamat Email</th>
                        <th>Role</th>
                        <th>Edit</th>
                        <th>Hapus</th>
                    </thead>
                    <tbody>
                        @forelse ($user as $index => $item)
                            <tr>
                                <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                <td class="border px-6 py-4">{{ $item->name }}</td>
                                <td class="border px-6 py-4">{{ $item->email }}</td>
                                <td class="border px-6 py-4">{{ $item->roles }}</td>
                                <td class="border px-6 py-4">

                                    <a href="{{ route('manageUser.edit', $item->id) }}" style="text-decoration:none;"
                                        class="btn btn-outline-warning">
                                        Edit
                                    </a>

                                </td>
                                <td class="border px-6 py-4">
                                    <form action="{{ route('manageUser.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <td colspan="6" class="border px-6 py-4 text-center">Tidak ada data</td>

                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
