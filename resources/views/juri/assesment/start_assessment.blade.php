@extends('juri.dashboard')
@section('content')
    <div class="container-fluid text-center position-absolute top-30">
        <div class="mt-5 justify-content-center ml-5 ml-60 mr-5 mr-60">
            <br>
            <br>
            <div class="text-center h3">{{ Breadcrumbs::render('startAssessment', $event) }}</div>
            <div class="card-body mb-5">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%">
                        <thead>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Aspek Penilaian</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @forelse ($contest as $index => $item)
                                <tr>
                                    <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="border px-6 py-4">{{ $item->contest->name }}</td>
                                    <td class="border px-6 py-4">{{ $item->contest->type }}</td>
                                    <td class="border px-6 py-4">{{ $item->contest->assessment_aspect }}</td>
                                    <td class="border px-6 py-4">
                                        @if (date('Y-m-d') > $event->end_date)
                                            <div class="text-center">Lomba sudah selesai</div>
                                        @else
                                            <a href="{{ route('juryAssessment.createAssessment', $item->contest->id) }}"
                                                class="btn btn-outline-primary">Mulai</a>
                                        @endif
                                        <a href="{{ route('juryAssessment.showAssessment', $item->contest->id) }}"
                                            class="btn btn-outline-success">Lihat data penilaian</a>
                                    </td>
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
