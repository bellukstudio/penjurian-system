<nav class="navbar navbar-expand-xl navbar-light bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03"
            aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="{{ route('dashboardUser') }}">Dashboard User <br>
            <strong class="h6">({{ Auth::user()->name }})</strong> </a>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Mengelola Juri
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('manageJuri.index') }}">Data Juri</a></li>
                        <li><a class="dropdown-item" href="{{ route('manageJuri.create') }}">Tambah Juri Baru</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Mengelola Acara
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('manageEvent.index') }}">Data Acara </a></li>
                        <li><a class="dropdown-item" href="{{ route('manageEvent.create') }}">Tambah Acara Baru</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Laporan
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('manageReport.dataContest') }}">Data Lomba </a>
                        <li><a class="dropdown-item" href="{{ route('manageReport.dataEvent') }}">Data Acara </a>
                        <li><a class="dropdown-item" href="{{ route('manageReport.dataParticipant') }}">Data Peserta
                            </a>
                        <li><a class="dropdown-item" href="{{ route('manageReport.dataAssessment') }}">Data Penilaian
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
            <form action="{{ route('logout') }}" method="POST" class="form-flex">
                @csrf
                <button type="submit" class="btn btn-toolbar">Keluar</button>

            </form>
        </div>
    </div>
</nav>
