@extends('admin.dashboard')
@section('content')
    <div class="text-start">
        <div class="ml-5">
            <h3 class="text-start">Laporan Penilaian</h3>
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
            <form class="form-inline" action="{{ route('manageReportAdmin.dataAssessment') }}" method="POST">
                @csrf
                <div class="form-group mb-2">
                    <input type="text" name="searchEventName" id="" class="form-control ml-2" placeholder="Nama Acara">
                </div>
                <div class="form-group mb-2">
                    <input type="text" name="searchContestName" id="" class="form-control ml-2" placeholder="Nama Lomba">
                </div>
                <div class="form-group mb-2">
                    <input type="text" name="searchEmailUser" id="" class="form-control ml-2" placeholder="Alamat Email">
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
                            <th>Nama Acara</th>
                            <th>Nama Lomba</th>
                            <th>Email User</th>
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
                                    <td class="border px-6 py-4">{{ $item->email_user }}</td>
                                    <td class="border px-6 py-4">{{ $item->aspek }}</td>
                                    <td class="border px-6 py-4">{{ $item->name_participants }}</td>
                                    <td class="border px-6 py-4">{{ $item->total_score }}</td>
                                    <td class="border px-6 py-4">{{ $item->average }}</td>


                                </tr>
                            @empty
                                <td colspan="8" class="border px-6 py-4 text-center">Tidak ada data</td>
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

                    @if ($data->currentPage() == $data->lastPage())
                        <a href="{{ $data->previousPageUrl() }}" class="text-decoration-none">
                            << Back</a>
                    @endif

                </div>
                <br><br><br>
            </div>
        </div>
    </div>

@endsection
