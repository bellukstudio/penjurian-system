@extends('juri.dashboard')
@section('content')
    <div class="container-fluid text-center position-absolute top-30">
        <div class="mt-5 justify-content-center ml-5 ml-60 mr-5 mr-60">
            <br>
            <div class="text-center h3">{{ Breadcrumbs::render('startAssessment.show', $event,$contest) }}</div>
            <div class="card-body mb-5">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%">
                        <thead>
                            <th>#</th>
                            <th>Nama Peserta</th>
                            <th>Nama Juri</th>
                            <th>Total Nilai</th>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                                <tr>
                                    <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="border px-6 py-4">{{ $item->peserta->name }}</td>
                                    <td class="border px-6 py-4">{{ $item->user->name }}</td>
                                    <td class="border px-6 py-4">{{ $item->score }}</td>

                                </tr>
                            @empty
                                <td colspan="5" class="border px-6 py-4">Tidak ada data</td>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
