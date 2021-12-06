@extends('user.dashboard')
@section('content')
    <div class="text-start">
        <div class="ml-5">
            <h3 class="text-start">Laporan Lomba</h3>
            <div class="container-sm">
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
            </div>
            <form class="form-inline" action="{{ route('manageReport.dataContest') }}" method="POST">
                @csrf
                <div class="form-group mb-2">
                    <input type="text" name="searchEventName" id="" class="form-control ml-2" placeholder="Nama Acara">
                </div>
                <button type="submit" class="btn btn-primary mb-2 ml-2" name="searchBtn"><i class="fa fa-search"></i>
                    Cari</button>
                <button class="btn btn-outline-info ml-2 mb-2" name="exportPdf" type="submit">
                    <i class="fa fa-print">Unduh PDF</i>
                </button>
            </form>

        </div>
        <div class="justify-content-center ml-2 mr-5 mr-60">
            <div class="card-body mb-5">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%">
                        <thead>
                            <th>#</th>
                            <th>Nama Lomba</th>
                            <th>Jenis Lomba (Kelompok / Individu)</th>
                            <th>Nama Acara</th>
                            <th>Aspek Penilaian</th>
                        </thead>
                        <tbody>
                            @forelse ($contest as $index => $item)
                                <tr>
                                    <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="border px-6 py-4">{{ $item->contest_name }}</td>
                                    <td class="border px-6 py-4">{{ $item->type_contest }}</td>
                                    <td class="border px-6 py-4">{{ $item->event_name }}</td>
                                    <td class="border px-6 py-4">{{ $item->contest_aspect }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="border px-6 py-4">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
                {{-- paginate --}}
                <div class="card-footer text-muted">
                    @if ($contest->hasMorePages())
                        <table>
                            <tr>
                                <td><a href="{{ $contest->previousPageUrl() }}" class="text-decoration-none">
                                        << Back</a>
                                </td>
                                <td>
                                    <h8>&nbsp;{{ $contest->currentPage() }} &nbsp;</h8>
                                </td>
                                <td><a href="{{ $contest->nextPageUrl() }}" class="text-decoration-none">Next >> </a></td>
                            </tr>
                        </table>
                    @endif
                    @if ($contest->currentPage() == $contest->lastPage())
                        <a href="{{ $contest->previousPageUrl() }}" class="text-decoration-none">
                            << Back</a>
                    @endif
                </div>
                <br><br><br>
            </div>
        </div>
    </div>

@endsection
