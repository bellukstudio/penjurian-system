@extends('juri.dashboard')
@section('content')
    <div class="container-fluid text-center position-absolute top-30">
        <div class="mt-5 justify-content-center ml-5 ml-60">
            @forelse ($event as $item)
                <div class="container">
                    <div class="row g-2">
                        <div class="col-3">
                            <div class="p-3 text-start">Nama Acara</div>
                        </div>
                        <div class="col-3">
                            <div class="p-3">:</div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 text-start">{{ $item->name }}</div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-3">
                            <div class="p-3 text-start">Alamat Acara</div>
                        </div>
                        <div class="col-3">
                            <div class="p-3">:</div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 text-start">{{ $item->address }}</div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-3">
                            <div class="p-3 text-start">Penanggung Jawab Acara</div>
                        </div>
                        <div class="col-3">
                            <div class="p-3">:</div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 text-start">{{ $item->name_person_responsible }}</div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-3">
                            <div class="p-3 text-start">Tanggal Mulai Acara</div>
                        </div>
                        <div class="col-3">
                            <div class="p-3">:</div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 text-start">{{ $item->start_date }}</div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-3">
                            <div class="p-3 text-start">Tanggal Berakhir Acara</div>
                        </div>
                        <div class="col-3">
                            <div class="p-3">:</div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 text-start">{{ $item->end_date }}</div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-2 mt-5">
                            <a href="{{ route('juryAssessment.startAssessment', $item->token) }}"
                                class="btn btn-outline-success text-start">Mulai Penjurian</a>
                        </div>
                        <div class="col-1 mt-5">
                            <form action="{{ route('dashboardJuri.removeToken', $item->token) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-outline-danger">
                                    Keluar
                                </button>
                            </form>

                        </div>

                    </div>
                </div>
            @empty
                <h3 class="text-center">Event tidak ada</h3>
            @endforelse
        </div>
    </div>
@endsection
