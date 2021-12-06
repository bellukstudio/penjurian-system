@extends('admin.dashboard')
@section('content')

    <div class="container text-start">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"{{ Breadcrumbs::render('dataEvents.edit',$item); }}</h1>
        </div>
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

        <form action="{{ route('manageEvents.update', $item->id) }}" method="POST" class="mb-5">
            @csrf
            @method('put')
            <div class="mb-3">
                <label for="exampleInputEventName" class="form-label">Nama Acara</label>
                <input type="text" class="form-control" id="exampleInputEventName" name="name"
                    value="{{ old('name') ?? $item->name }}" placeholder="Class Meeting">
            </div>
            <div class="mb-3">
                <label for="exampleInputPJ" class="form-label">Nama Penanggung Jawab</label>
                <input type="text" class="form-control" id="exampleInputPj" name="name_person_responsible"
                    value="{{ old('name_person_responsible') ?? $item->name_person_responsible }}" placeholder="Jhon doe">
            </div>

            <div class="mb-3">
                <label for="exampleInputAddress" class="form-label">Alamat Acara</label>
                <textarea class="form-control" id="exampleInputPj" name="address" cols="30" rows="10"
                    placeholder="Street,45">{{ old('address') ?? $item->address }}</textarea>
            </div>

            <div class="mb-3">
                <label for="exampleInputStartDate" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="exampleInputStartDate" name="start_date"
                    value="{{ old('start_date') ?? $item->start_date }}">
            </div>

            <div class="mb-3">
                <label for="exampleInputEndDate" class="form-label">Tanggal Berakhir</label>
                <input type="date" class="form-control" id="exampleInputEndDate" name="end_date"
                    value="{{ old('end_date') ?? $item->end_date }}">
            </div>


            <button type="submit" class="btn btn-primary">Ubah</button>
        </form>
    </div>
@endsection
