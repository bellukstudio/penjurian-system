@extends('admin.dashboard')
@section('content')
    <div class="container">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pilih Juri {{ $contest->name }}</h1>
        </div>
        {{-- form search --}}
        <form class="form-inline" action="{{ route('manageJury.createJuryInContest', [$contest->id, $event->id]) }}"
            method="GET">
            <div class="form-group mb-2">
                <input type="text" name="searchJuri" class="form-control ml-2" placeholder="Nama Juri">
            </div>
            <button type="submit" class="btn btn-primary mb-2 ml-2"><i class="fa fa-search"></i> Cari</button>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered mt-5" width="100%">
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
                            <td class="border px-6 py-4" width="20%">
                                <button class="btn btn-outline-primary btnPilih" data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}">Pilih</button>
                            </td>
                        </tr>
                    @empty
                        <td colspan="5" class="border px-6 py-4 text-center">Tidak ada data</td>

                    @endforelse
                </tbody>
            </table>
            {{-- paginate --}}
            <div class="card-footer text-muted">
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
        </div>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-5">
            <h1 class="h3 mb-0 text-gray-800">
                {{ Breadcrumbs::render('dataContest.createJuryContest', $contest, $event) }}</h1>

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
        <form action="{{ route('manageJury.storeJuryForContest') }}" method="POST" class="mb-5">
            @csrf
            <div class="mb-3">
                <label for="exampleInput" class="form-label">ID User </label>
                <input type="text" class="form-control" id="idUser" name="id_user" value="{{ old('id_user') }}"
                    readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInput" class="form-label">Nama User</label>
                <input type="text" class="form-control" id="nameUser" name="nameUser" value="{{ old('nameUser') }}"
                    readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInput" class="form-label">Nama Lomba</label>
                <input type="hidden" name="id_contest" value="{{ $contest->id }}">
                <input type="text" class="form-control" id="nameContest" name="nameContest"
                    value="{{ old('nameContest') ?? $contest->name }}" readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInput" class="form-label">Nama Acara</label>
                <input type="hidden" name="id_event" value="{{ $event->id }}">
                <input type="text" class="form-control" id="nameEvent" name="nameEvent"
                    value="{{ old('nameEvent') ?? $event->name }}" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(function() {
        /// jika class btnPilih di klik
        $(".btnPilih").each(function() {
            $(this).click(function() {
                var id = $(this).data("id");
                var name = $(this).data("name");
                document.getElementById('idUser').value = id;
                document.getElementById('nameUser').value = name;
            })
        });
    })
</script>
