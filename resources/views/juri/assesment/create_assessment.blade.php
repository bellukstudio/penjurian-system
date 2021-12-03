@extends('juri.dashboard')
@section('content')
    <div class="container-fluid  position-absolute top-30">
        <div class="mt-5 justify-content-center ml-5 ml-60 mr-5 mr-60 mb-5 mb-20">
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
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session()->has('assessmentFailed'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('assessmentFailed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @forelse ($participant as $item)
                <br>
                <div class="container-sm">
                    <div class="card text-center mb-5 p-20">
                        <div class="card-header">
                            Data Peserta {{ $contest->name }}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Nama Peserta : {{ $item->name }}</h5>
                            <p class="card-text">Nomor Urut : {{ $item->serial_number }}
                            </p>
                        </div>
                        <div class="card-footer text-muted">
                            @if ($participant->currentPage() == $participant->lastPage())
                                <div></div>
                            @else
                                <div class="text-center">
                                    <a href="{{ $participant->nextPageUrl() }}" rel="next"
                                        class="btn btn-outline-primary">Peserta
                                        Selanjutnya </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <form action="{{ route('juryAssessment.saveAssessment', [$contest->id, $item->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $item->id }}" name="id_participant">
                    @foreach (explode(',', $contest->assessment_aspect) as $aspect)
                        <div class="container-sm">
                            <div class="mb-3">
                                <label for="exampleInput" class="form-label">{{ $aspect }}</label>
                                <input type="number" class="form-control" id="exampleInput"
                                    name="{{ str_replace(' ', '_', strtolower($aspect)) }}" autocomplete="off"
                                    value="{{ old(str_replace(' ', '_', strtolower($aspect))) }}" required>
                            </div>
                        </div>
                    @endforeach
                    <div class="container-sm">
                        <button type="submit" class="btn btn-outline-primary">Save</button>
                    </div>
                </form>
            @empty
                <div class="container">
                    <div class="row g-1" style="margin-top: 250px;">
                        <div class="col-12">
                            <div class="p-5 text-center h3">
                                Tidak ada peserta untuk dinilai
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <br><br><br>
    </div>
@endsection
