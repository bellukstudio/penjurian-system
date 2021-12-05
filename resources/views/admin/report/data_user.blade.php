@extends('admin.dashboard')
@section('content')
    <div class="text-start">
        <div class="ml-5">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Laporan User</h1>
            </div>
            <div class="container-sm">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        </p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            <form class="form-inline" action="" method="POST">
                @csrf
                <div class="form-group mb-2">
                    <input type="text" name="searchRoles" id="" class="form-control ml-2" placeholder="Cari Role">
                </div>
                <button class="btn btn-outline-info ml-2 mb-2" name="searchBtn" type="submit">
                    <i class="fa fa-search">Cari</i>
                </button>
                <button class="btn btn-outline-info ml-2 mb-2" name="exportPdf" type="submit">
                    <i class="fa fa-print">Unduh PDF</i>
                </button>
            </form>

        </div>
        <div class="justify-content-center ml-2 mr-5 mr-60">
            <div class="card-body mb-5">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%">
                        <thead>
                            <th>#</th>
                            <th>Nama User</th>
                            <th>Alamat Email</th>
                            <th>Role</th>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                                <tr>
                                    <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="border px-6 py-4">{{ $item->name }}</td>
                                    <td class="border px-6 py-4">{{ $item->email }}</td>
                                    <td class="border px-6 py-4">{{ $item->roles }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="border px-6 py-4">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
                {{-- paginate --}}
                <div class="card-footer text-muted">
                    @if ($data->hasMorePages())
                        <table>
                            <tr>
                                <td><a href="{{ $data->previousPageUrl() }}" class="text-decoration-none">
                                        << Back</a>
                                </td>
                                <td>
                                    <h8>&nbsp;{{ $data->currentPage() }} &nbsp;</h8>
                                </td>
                                <td><a href="{{ $data->nextPageUrl() }}" class="text-decoration-none">Next >> </a>
                                </td>
                            </tr>
                        </table>
                    @endif
                    @if ($data->currentPage() == $data->lastPage())
                        <a href="{{ $data->previousPageUrl() }}" class="text-decoration-none">
                            << Back</a>
                    @endif
                </div>
                <br><br><br>
            </div>
        </div>
    </div>
@endsection
