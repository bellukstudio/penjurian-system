@extends('user.dashboard')
@section('content')
    <div class="text-start">
        <div class="ml-5">
            <h3 class="text-start">Laporan Penilaian</h3>
            <form class="form-inline" action="{{ route('manageReport.dataAssessment') }}" method="GET">
                <div class="form-group mb-2">
                    <input type="text" name="searchEventName" id="" class="form-control ml-2" placeholder="Nama Acara">
                </div>
                <div class="form-group mb-2">
                    <input type="text" name="searchContestName" id="" class="form-control ml-2" placeholder="Nama Lomba">
                </div>
                <button type="submit" class="btn btn-primary mb-2 ml-2"><i class="fa fa-search"></i> Cari</button>
            </form>

        </div>
        <div class="justify-content-center ml-5 ml-60 mr-5 mr-60">
            <div class="card-body mb-5">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%">
                        <thead>
                            <th>#</th>
                            <th>Nama Acara</th>
                            <th>Nama Lomba</th>
                            <th>Aspek Penilaian</th>
                            <th>Nama Peserta</th>
                            <th>Total Nilai</th>
                            <th>Rata Rata</th>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                                <tr>
                                    <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="border px-6 py-4">{{ $item->name_event }}</td>
                                    <td class="border px-6 py-4">{{ $item->name_contest }}</td>
                                    <td class="border px-6 py-4">{{ $item->aspek }}</td>
                                    <td class="border px-6 py-4">{{ $item->name_participants }}</td>
                                    <td class="border px-6 py-4">{{ $item->total_score }}</td>
                                    <td class="border px-6 py-4">{{ $item->average }}</td>
                                

                                </tr>
                            @empty
                                <td colspan="6" class="border px-6 py-4">Tidak ada data</td>
                            @endforelse

                        </tbody>

                    </table>
                </div>
                {{-- paginate --}}
                <div class="card-footer text-muted">
                    @if ($data->hasMorePages())
                        <table>
                            <tr>
                                <td><a href="{{ $data->previousPageUrl() }}" class="text-decoration-none">
                                        << Back</a>
                                </td>
                                <td>
                                    <h8>&nbsp;{{ $data->currentPage() }} &nbsp;</h8>
                                </td>
                                <td><a href="{{ $data->nextPageUrl() }}" class="text-decoration-none">Next >> </a></td>
                            </tr>
                        </table>
                    @endif

                </div>
                <br><br><br>
            </div>
        </div>
    </div>

@endsection
