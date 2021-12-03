@extends('user.dashboard')
@section('content')
    <div class="container text-start">
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
        <h3 class="text-center h3">Pendaftaran Lomba {{ $contest->name }}</h3><br>
        <form action="{{ route('manageParticipant.store') }}" method="POST" class="mb-5">
            @csrf
            <input type="hidden" class="form-control" name="idContest" value="{{ $contest->id }}">
            <input type="hidden" class="form-control" name="idEvent" value="{{ $idEvent }}">
            <div class="mb-3">
                <label for="exampleInputname" class="form-label">Nama Peserta (Jika Kelompok masukan <strong>nama perwakilan</strong>)</label>
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
                    value="{{ $serial_number }}" readonly>
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
