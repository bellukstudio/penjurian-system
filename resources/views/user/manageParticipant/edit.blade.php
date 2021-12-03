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
        <h3 class="text-center h3">Edit Peserta {{ $participant->name }}</h3><br>
        <form action="{{ route('manageParticipant.update', $participant->id) }}" method="POST" class="mb-5">
            @csrf
            @method('put')
            <input type="hidden" class="form-control" name="id_contest" value="{{ $participant->id_contest }}">
            <input type="hidden" class="form-control" name="id_event" value="{{ $participant->id_event }}">

            <div class="mb-3">
                <label for="exampleInputname" class="form-label">Nama Peserta (Jika Kelompok masukan <strong>nama perwakilan</strong>)</label>
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
