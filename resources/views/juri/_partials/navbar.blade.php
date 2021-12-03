<nav class="navbar navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboardJuri') }}">Dashboard Juri <br> <strong>({{ Auth::user()->name }})</strong></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <form action="{{ route('logout') }}" method="POST" class="form-flex">
                    @csrf
                    <button type="submit" class="btn btn-toolbar">Keluar</button>

                </form>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a href="{{ route('juryAssessment.indexAssessment') }}" class="nav-link">Data
                            Penjurian</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
