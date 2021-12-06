@extends('admin.dashboard')
@section('content')
    <div class="container text-start">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pilih Rujukan User</h1>
        </div>
        {{-- form search --}}
        <form class="form-inline" action="{{ route('manageJury.create') }}" method="GET">
            <div class="form-group mb-2">
                <input type="text" name="searchUser" class="form-control ml-2" placeholder="Nama User">
            </div>
            <button type="submit" class="btn btn-primary mb-2 ml-2"><i class="fa fa-search"></i> Cari</button>
        </form>
        <div class="table-responsive mb-5">
            <table class="table table-bordered" width="100%">
                <thead>
                    <th>#</th>
                    <th>Nama User</th>
                    <th>Alamat Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @forelse ($user as $index => $item)
                        <tr>
                            <td class="border px-6 py-4">{{ $index + 1 }}</td>
                            <td class="border px-6 py-4">{{ $item->name }}</td>
                            <td class="border px-6 py-4">{{ $item->email }}</td>
                            <td class="border px-6 py-4">{{ $item->roles }}</td>
                            <td class="border px-6 py-4">
                                <button class="btn btn-outline-primary btnPilih" data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}">Pilih</button>
                            </td>
                        </tr>
                    @empty
                        <td colspan="5" class="border px-6 py-4 text-center">Tidak ada data</td>

                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- paginate --}}
        <div class="card-footer text-muted mt-3 mb-3">
            @if ($user->hasMorePages())
                <table>
                    <tr>
                        <td><a href="{{ $user->previousPageUrl() }}" class="text-decoration-none">
                                << Back</a>
                        </td>
                        <td>
                            <h8>&nbsp;{{ $user->currentPage() }} &nbsp;</h8>
                        </td>
                        <td><a href="{{ $user->nextPageUrl() }}" class="text-decoration-none">Next >> </a></td>
                    </tr>
                </table>
            @endif
        </div>
        {{-- form tambah juri --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-5">
            <h1 class="h3 mb-0 text-gray-800">{{ Breadcrumbs::render('dataJuri.create'); }}</h1>
        </div>
        @if (session()->has('juryFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('juryFailed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
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
        <form action="{{ route('manageJury.store') }}" method="POST" class="mb-5">
            @csrf
            <div class="mb-3">
                <label for="exampleInput" class="form-label">ID User Rujukan</label>
                <input type="text" class="form-control" id="idUser" name="id_user" value="{{ old('id_user') }}"
                    readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInput" class="form-label">Nama User Rujukan</label>
                <input type="text" class="form-control" id="nameUser" name="nameUser" value="{{ old('nameUser') }}"
                    readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInputFullName" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="exampleInputFullName" name="name" value="{{ old('name') }}"
                    placeholder="Jhon Doe">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">AlamatEmail</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email"
                    value="{{ old('email') }}" placeholder="jhondoe@gmail.com">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password"
                    placeholder="Password">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(function() {
        // jika button dengan class .btnPilih di klik
        $('.btnPilih').each(function() {
            $(this).click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                document.getElementById('idUser').value = id;
                document.getElementById('nameUser').value = name;
            })
        })
    });
</script>
