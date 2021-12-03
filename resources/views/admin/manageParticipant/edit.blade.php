@extends('admin.dashboard')
@section('content')

    <div class="container text-start">
        {{-- Form input --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pilih Lomba</h1>
        </div>
        <form class="form-inline" action="{{ route('manageParticipants.edit', $participant->id) }}" method="GET">
            <div class="form-group mb-2">
                <input type="text" name="searchContest" class="form-control ml-2" placeholder="Nama Lomba">
            </div>
            <button type="submit" class="btn btn-primary mb-2 ml-2"><i class="fa fa-search"></i> Cari</button>
        </form>
        {{-- table acara --}}
        <div class="card-body mb-5">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <th>#</th>
                        <th>Nama Acara</th>
                        <th>Nama Lomba</th>
                        <th>Jenis Lomba (Kelompok / Individu)</th>
                        <th>Aspek Penilaian</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($dataContest as $index => $item)
                            <tr>
                                <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                <td class="border px-6 py-4">{{ $item->acara->name }}</td>
                                <td class="border px-6 py-4">{{ $item->name }}</td>
                                <td class="border px-6 py-4">{{ $item->type }}</td>
                                <td class="border px-6 py-4">{{ $item->assessment_aspect }}</td>
                                <td class="border px-6 py-4" wudth="30%">
                                    <button class="btn btn-sm btn-outline-primary btnPilih" data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}">Pilih</button>
                                </td>
                            </tr>
                        @empty
                            <td colspan="8" class="border px-6 py-4 text-center">Tidak ada data</td>

                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- paginate --}}
            <div class="card-footer text-muted">
                @if ($dataContest->hasMorePages())
                    <table>
                        <tr>
                            <td><a href="{{ $dataContest->previousPageUrl() }}" class="text-decoration-none">
                                    << Back</a>
                            </td>
                            <td>
                                <h8>&nbsp;{{ $dataContest->currentPage() }} &nbsp;</h8>
                            </td>
                            <td><a href="{{ $dataContest->nextPageUrl() }}" class="text-decoration-none">Next >> </a>
                            </td>
                        </tr>
                    </table>
                @endif

            </div>
        </div>
        {{-- form input peserta --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Data Peserta {{ $participant->name }}</h1>
        </div>
        @if (session()->has('participantFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('participantFailed') }}
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
        <form action="{{ route('manageParticipants.update',$participant->id) }}" method="POST" class="mb-5">
            @csrf
            @method('put')
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">ID Lomba</label>
                <input type="text" class="form-control" id="id_contest" name="id_contest"
                    value="{{ old('id_contest') ?? $getContest->id }}" placeholder="ID Lomba" readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">Nama Lomba</label>
                <input type="text" class="form-control" id="nameContest" name="nameContest"
                    value="{{ old('nameContest') ?? $getContest->name }}" placeholder="Nama Lomba" readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">Nama Peserta (Jika Kelompok masukan <strong>nama
                        perwakilan</strong>)</label>
                <input type="text" class="form-control" id="exampleInputname" name="name"
                    value="{{ old('name') ?? $participant->name }}">
            </div>
            <div class="mb-3">
                <label for="exampleInputphone" class="form-label">Nomor Telepon</label>
                <input type="number" class="form-control" id="exampleInputphone" name="phone"
                    value="{{ old('phone') ?? $participant->phone }}">
            </div>
            <div class="mb-3">
                <label for="exampleInputaddress" class="form-label">Alamat</label>
                <textarea name="address" id="exampleInputaddress" cols="30" rows="10"
                    class="form-control">{{ old('address') ?? $participant->address }}</textarea>
            </div>
            <div class="mb-3">
                <label for="exampleInputJk" class="form-label">Jenis Kelamin</label>
                <select name="gender" id="exampleInputJk" class="form-select">
                    @if ($participant->gender == 'L')
                        <option value="L">Laki - Laki</option>
                        <option value="P">Perempuan</option>
                    @else
                        <option value="P">Perempuan</option>
                        <option value="L">Laki - Laki</option>
                    @endif
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ubah</button>
        </form>
    </div>
@endsection
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(function() {
        // ambil class btnPilih pada tabel
        $('.btnPilih').each(function() {
            $(this).click(function() {
                var idContest = $(this).data('id');
                var nameContest = $(this).data('name');
                document.getElementById('id_contest').value = idContest;
                document.getElementById('nameContest').value = nameContest;
            })
        })
    })
</script>
