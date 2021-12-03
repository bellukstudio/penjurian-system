@extends('user.dashboard')
@section('content')
    <div class="container text-start">
        @if (session()->has('raceFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('raceFailed') }}
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
        <h3 class="text-center h3">Tambah Lomba Di Acara {{ $event->name }}</h3><br>

        <form action="{{ route('manageContest.store') }}" method="POST" class="mb-5">
            @csrf
            <input type="hidden" class="form-control" name="id_user" value="{{ Auth::user()->id }}">
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">Nama Lomba</label>
                <input type="text" class="form-control" id="exampleInputname" name="name" value="{{ old('name') }}" placeholder="Pidato">
            </div>
            <div class="mb-3">
                <label for="exampleInputAspect" class="form-label">Aspek Penilaian <strong>Pisahkan dengan tanda (,)</strong> contoh: Intonasi,Artikulasi</label>
                <input type="text" class="form-control" id="exampleInputAspect" name="assessment_aspect" value="{{ old('assessment_aspect') }}" placeholder="Contoh: Intonasi,Artikulasi">
            </div>

            <div class="mb-3">
                <label for="exampleInputType" class="form-label">Pilih Jenis Lomba</label>
                <select name="type" id="" class="form-select">
                    <option value="">Pilih Jenis Lomba</option>
                    <option value="KELOMPOK">KELOMPOK</option>
                    <option value="INDIVIDU">INDIVIDU</option>
                </select>
            </div>
            <input type="hidden" class="form-control" name="id_event" value="{{ $event->id }}">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
