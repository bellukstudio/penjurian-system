@extends('admin.dashboard')
@section('content')
    <div class="container text-start">
        {{-- Form input --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Cari User</h1>
        </div>
        <form class="form-inline" action="{{ route('manageEvents.create') }}" method="GET">
            <div class="form-group mb-2">
                <input type="text" name="search" class="form-control ml-2" placeholder="Nama User">
            </div>
            <button type="submit" class="btn btn-primary mb-2 ml-2"><i class="fa fa-search"></i> Cari</button>
        </form>
        <div class="table-responsive mt-4">
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
                            <td class="border px-6 py-4" width="20%">
                                <button class="btn btn-sm btn-outline-primary btnPilih" data-id="{{ $item->id }}"
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

        {{-- form tambah acara --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Acara</h1>
        </div>
        @if (session()->has('eventFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('eventFailed') }}
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
        <form action="{{ route('manageEvents.store') }}" method="POST" class="mb-5">
            @csrf
            <div class="mb-3">
                <label for="exampleInputEventName" class="form-label">ID User</label>
                <input type="text" class="form-control" id="id_user" name="id_user" value="{{ old('id_user') }}"
                    readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInputEventName" class="form-label">Nama User</label>
                <input type="text" class="form-control" id="nameUser" name="nameUser" value="{{ old('nameUser') }}"
                    readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInputEventName" class="form-label">Nama Acara</label>
                <input type="text" class="form-control" id="exampleInputEventName" name="name"
                    value="{{ old('name') }}" placeholder="Class Meeting">
            </div>
            <div class="mb-3">
                <label for="exampleInputPJ" class="form-label">Nama Penanggung Jawab</label>
                <input type="text" class="form-control" id="exampleInputPj" name="name_person_responsible"
                    value="{{ old('name_person_responsible') }}" placeholder="Jhon doe">
            </div>

            <div class="mb-3">
                <label for="exampleInputAddress" class="form-label">Alamat Acara</label>
                <textarea class="form-control" id="exampleInputPj" name="address" cols="30" rows="10"
                    placeholder="Street,45">{{ old('address') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="exampleInputStartDate" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="exampleInputStartDate" name="start_date"
                    value="{{ old('start_date') }}">
            </div>

            <div class="mb-3">
                <label for="exampleInputEndDate" class="form-label">Tanggal Berakhir</label>
                <input type="date" class="form-control" id="exampleInputEndDate" name="end_date"
                    value="{{ old('end_date') }}">
            </div>


            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
<script src="{{ asset('js/jquery.min.js') }}"></script>

<script>
    // jika klik tombol pilih di tabel user
    $(function() {
        $(".btnPilih").each(function() {
            $(this).click(function() {
                var id = $(this).data("id");
                var name = $(this).data("name");
                document.getElementById('id_user').value = id;
                document.getElementById('nameUser').value = name;
            })
        });
    })
</script>
