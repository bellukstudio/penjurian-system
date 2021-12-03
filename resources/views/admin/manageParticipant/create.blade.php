@extends('admin.dashboard')
@section('content')

    <div class="container text-start">
        {{-- form input peserta --}}

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Data Peserta Lomba {{ $contest->name }}</h1>
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
        <form action="{{ route('manageParticipants.store') }}" method="POST" class="mb-5">
            @csrf
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">ID Acara</label>
                <input type="text" class="form-control" id="idEvent" name="id_event" placeholder="ID Acara"
                    value="{{ old('id_event') ?? $contest->id_event }}" readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">Nama Event</label>
                <input type="text" class="form-control" id="nameEvent" name="nameEvent" placeholder="Nama Acara"
                    value="{{ old('namaEvent') ?? $contest->acara->name }}" readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">ID Lomba</label>
                <input type="text" class="form-control" id="id_contest" name="id_contest"
                    value="{{ old('id_contest') ?? $contest->id }}" placeholder="ID Lomba" readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">Nama Lomba</label>
                <input type="text" class="form-control" id="nameContest" name="nameContest"
                    value="{{ old('nameContest') ?? $contest->name }}" placeholder="Nama Lomba" readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">Nama Peserta (Jika Kelompok masukan <strong>nama
                        perwakilan</strong>)</label>
                <input type="text" class="form-control" id="exampleInputname" name="name" value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label for="exampleInputphone" class="form-label">Nomor Telepon</label>
                <input type="number" class="form-control" id="exampleInputphone" name="phone"
                    value="{{ old('phone') }}">
            </div>
            <div class="mb-3">
                <label for="exampleInputaddress" class="form-label">Alamat</label>
                <textarea name="address" id="exampleInputaddress" cols="30" rows="10"
                    class="form-control">{{ old('address') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="exampleInputSerialNumber" class="form-label">Nomor Urut</label>
                <input type="number" class="form-control" id="exampleInputSerialNumber" name="serial_number"
                    value="{{ old('serial_number') ?? $serial_number }}" readonly>
            </div>
            <div class="mb-3">
                <label for="exampleInputJk" class="form-label">Jenis Kelamin</label>
                <select name="gender" id="exampleInputJk" class="form-select">
                    <option value="">Jenis Kelamin</option>
                    <option value="L">Laki - Laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
