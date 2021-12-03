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
        <h3 class="text-center h3">Edit Lomba {{ $contest->name }}</h3><br>

        <form action="{{ route('manageContest.update', $contest->id) }}" method="POST" class="mb-5">
            @csrf
            @method('put')
            <input type="hidden" class="form-control" name="id_event" value="{{ $contest->id_event }}">
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">Nama Lomba</label>
                <input type="text" class="form-control" id="exampleInputname" name="name"
                    value="{{ old('name') ?? $contest->name }}">
            </div>
            <div class="mb-3">
                <label for="exampleInputAspect" class="form-label">Aspek Penilaian Lomba <strong>Pisahkan dengan tanda
                        (,)</strong> contoh: Intonasi,Artikulasi</label>
                <input type="text" class="form-control" id="exampleInputAspect" name="assessment_aspect"
                    value="{{ old('assessment_aspect') ?? $contest->assessment_aspect }}">
            </div>
            <div class="mb-3">
                <label for="exampleInputType" class="form-label">Pilih Tipe Lomba</label>
                <select name="type" id="" class="form-select">
                    <option value="{{ $contest->type }}">Pilih Tipe Lomba (Jika ingin ganti tipe lomba, Jika tidak kosongkan
                        saja)</option>
                    <option value="KELOMPOK">KELOMPOK</option>
                    <option value="INDIVIDU">INDIVIDU</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Ubah</button>
        </form>
    </div>
@endsection
