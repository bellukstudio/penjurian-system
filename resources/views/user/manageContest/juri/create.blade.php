@extends('user.dashboard')
@section('content')
    <div class="container text-start">
        <h5 class="container-sm text-center mb-5">
            {{ Breadcrumbs::render('dataEventsUser.showContest.showJury.create', $contest, $event) }}</h5>
        <br>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pilih Juri</h1>
        </div>
        {{-- form search --}}
        <form class="form-inline" action="{{ route('manageJuri.saveJury', $contest->id) }}" method="GET">
            <div class="form-group mb-2">
                <input type="text" name="searchJuri" class="form-control ml-2" placeholder="Nama Juri">
            </div>
            <button type="submit" class="btn btn-primary mb-2 ml-2"><i class="fa fa-search"></i> Cari</button>
        </form>
        <div class="table-responsive mb-5 mt-3">
            <table class="table table-bordered" width="100%">
                <thead>
                    <th>#</th>
                    <th>Nama User</th>
                    <th>Alamat Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @forelse ($juri as $index => $item)
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
            @if ($juri->hasMorePages())
                <table>
                    <tr>
                        <td><a href="{{ $juri->previousPageUrl() }}" class="text-decoration-none">
                                << Back</a>
                        </td>
                        <td>
                            <h8>&nbsp;{{ $juri->currentPage() }} &nbsp;</h8>
                        </td>
                        <td><a href="{{ $juri->nextPageUrl() }}" class="text-decoration-none">Next >> </a></td>
                    </tr>
                </table>
            @endif
        </div>
        {{-- form input jury --}}
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
        <form action="{{ route('manageJuri.storeJuryToContest') }}" method="POST" class="mb-5">
            @csrf
            <input type="hidden" name="id_contest" value="{{ $contest->id }}">
            <input type="hidden" name="id_event" value="{{ $contest->id_event }}">
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">ID Juri</label>
                <input type="text" class="form-control" id="id_juri" name="id_juri" value="{{ old('id_juri') }}"
                    readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">Nama Juri</label>
                <input type="text" class="form-control" id="nama_juri" name="nama_juri" value="{{ old('nama_juri') }}"
                    readonly>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
        <br><br><br>
    </div>
@endsection
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(function() {
        $('.btnPilih').each(function() {
            $(this).click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                document.getElementById('id_juri').value = id;
                document.getElementById('nama_juri').value = name;
            })
        })
    })
</script>
