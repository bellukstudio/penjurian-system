@extends('user.dashboard')
@section('content')
    <div class="text-start">
        <div class="ml-5">
            <h3 class="text-start">Laporan Acara</h3>
            <form action="{{ route('manageReport.dataEvent') }}" method="POST">
                @csrf
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
                            <th>Penanggung Jawab</th>
                            <th>Alamat Acara</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Berakhir</th>
                        </thead>
                        <tbody>
                            @forelse ($event as $index => $item)
                                <tr>
                                    <td class="border px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="border px-6 py-4">{{ $item->name }}</td>
                                    <td class="border px-6 py-4">{{ $item->name_person_responsible }}</td>
                                    <td class="border px-6 py-4">{{ $item->address }}</td>
                                    <td class="border px-6 py-4">{{ $item->start_date }}</td>
                                    <td class="border px-6 py-4">{{ $item->end_date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border px-6 py-4">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
                {{-- paginate --}}
                <div class="card-footer text-muted">
                    @if ($event->hasMorePages())
                        <table>
                            <tr>
                                <td><a href="{{ $event->previousPageUrl() }}" class="text-decoration-none">
                                        << Back</a>
                                </td>
                                <td>
                                    <h8>&nbsp;{{ $event->currentPage() }} &nbsp;</h8>
                                </td>
                                <td><a href="{{ $event->nextPageUrl() }}" class="text-decoration-none">Next >> </a></td>
                            </tr>
                        </table>
                    @endif
                    @if ($event->currentPage() == $event->lastPage())
                        <a href="{{ $data->previousPageUrl() }}" class="text-decoration-none">
                            << Back</a>
                    @endif
                </div>
                <br><br><br>
            </div>
        </div>
    </div>

@endsection
