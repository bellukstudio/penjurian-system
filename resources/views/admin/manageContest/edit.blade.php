@extends('admin.dashboard')
@section('content')
    <div class="container">
        {{-- Form input --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pilih Acara</h1>
        </div>
        <form class="form-inline" action="{{ route('manageContests.edit',$contest->id) }}" method="GET">
            <div class="form-group mb-2">
                <input type="text" name="search" class="form-control ml-2" placeholder="Nama Acara">
            </div>
            <button type="submit" class="btn btn-primary mb-2 ml-2"><i class="fa fa-search"></i> Cari</button>
        </form>
        <div class="card-body mb-5">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <th>#</th>
                        <th>Nama Acara</th>
                        <th>Penanggung Jawab</th>
                        <th>Alamat Acara</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Berakhir</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($event as $index => $item)
                            <tr>
                                <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                <td class="border px-6 py-4">{{ $item->name }}</td>
                                <td class="border px-6 py-4">{{ $item->name_person_responsible }}</td>
                                <td class="border px-6 py-4">{{ $item->address }}</td>
                                <td class="border px-6 py-4">{{ $item->start_date }}</td>
                                <td class="border px-6 py-4">{{ $item->end_date }}</td>
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
                @if ($event->hasMorePages())
                    <table>
                        <tr>
                            <td><a href="{{ $event->previousPageUrl() }}" class="text-decoration-none">
                                    << Back</a>
                            </td>
                            <td>
                                <h8>&nbsp;{{ $event->currentPage() }} &nbsp;</h8>
                            </td>
                            <td><a href="{{ $event->nextPageUrl() }}" class="text-decoration-none">Next >> </a></td>
                        </tr>
                    </table>
                @endif

            </div>
        </div>


        {{-- Form input --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Lomba</h1>
        </div>
        @if (session()->has('contestFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('contestFailed') }}
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

        <form action="{{ route('manageContests.update',$contest->id) }}" method="POST" class="mb-5">
            @csrf
            @method('put')
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">ID Event</label>
                <input type="text" class="form-control" id="idEvent" name="id_event" placeholder="ID Event"
                    value="{{ old('id_event') ?? $contest->id_event }}" readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">Nama Event</label>
                <input type="text" class="form-control" id="nameEvent" name="nameEvent" placeholder="Nama Event" value="{{ old('namaEvent') ?? $getEvent->name }}" readonly>
            </div>
            <input type="hidden" class="form-control" name="id_user" value="{{ Auth::user()->id }}">
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">Nama Lomba</label>
                <input type="text" class="form-control" id="exampleInputname" name="name"
                    value="{{ old('name') ?? $contest->name }}" placeholder="Pidato">
            </div>
            <div class="mb-3">
                <label for="exampleInputAspect" class="form-label">Aspek Penilaian <strong>Pisahkan dengan tanda
                        (,)</strong> contoh: Intonasi,Artikulasi</label>
                <input type="text" class="form-control" id="exampleInputAspect" name="assessment_aspect"
                    value="{{ old('assessment_aspect') ?? $contest->assessment_aspect }}"
                    placeholder="Contoh: Intonasi,Artikulasi">
            </div>

            <div class="mb-3">
                <label for="exampleInputType" class="form-label">Pilih Tipe Lomba</label>
                <select name="type" id="" class="form-select">
                    <option value="{{ $contest->type }}">Pilih Tipe Lomba (Jika ingin ganti tipe lomba, Jika tidak
                        kosongkan
                        saja)</option>
                    <option value="KELOMPOK">KELOMPOK</option>
                    <option value="INDIVIDU">INDIVIDU</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ubah</button>
        </form>
    </div>
@endsection
<script src="{{ asset('js/jquery.min.js') }}"></script>

<script>
    // jika klik tombol pilih di tabel acara
    $(function() {
        $(".btnPilih").each(function() {
            $(this).click(function() {
                var idEvent = $(this).data("id");
                var nameEvent = $(this).data("name");
                document.getElementById('idEvent').value = idEvent;
                document.getElementById('nameEvent').value = nameEvent;
            })
        });
    })
</script>
