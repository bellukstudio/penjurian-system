@extends('user.dashboard')
@section('content')
    <div class="container text-start">
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
        <form action="{{ route('manageEvent.store') }}" method="POST" class="mb-5">
            @csrf
            <input type="hidden" class="form-control" name="id_user" value="{{ Auth::user()->id }}">
            <div class="mb-3">
                <label for="exampleInputEventName" class="form-label">Nama Acara</label>
                <input type="text" class="form-control" id="exampleInputEventName" name="name" value="{{ old('name') }}" placeholder="Class Meeting">
            </div>
            <div class="mb-3">
                <label for="exampleInputPJ" class="form-label">Nama Penanggung Jawab</label>
                <input type="text" class="form-control" id="exampleInputPj" name="name_person_responsible"  value="{{ old('name_person_responsible') }}" placeholder="Jhon doe">
            </div>

            <div class="mb-3">
                <label for="exampleInputAddress" class="form-label">Alamat Acara</label>
                <textarea class="form-control" id="exampleInputPj" name="address" cols="30" rows="10" placeholder="Street,45">{{ old('address') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="exampleInputStartDate" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="exampleInputStartDate" name="start_date" value="{{ old('start_date') }}">
            </div>

            <div class="mb-3">
                <label for="exampleInputEndDate" class="form-label">Tanggal Berakhir</label>
                <input type="date" class="form-control" id="exampleInputEndDate" name="end_date" value="{{ old('end_date') }}">
            </div>


            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <br><br><br>
@endsection
