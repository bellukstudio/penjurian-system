<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('landing-page/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-page/css/boxicons.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('landing-page/css/style.css') }}" />

    <title>Aplikasi Penjurian</title>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg py-3 sticky-top navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="#">
                <h3>PENJURIAN</h3>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">FITUR</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">HUBUNGI KAMI</a>
                    </li>
                </ul>
                <a class="btn btn-primary ms-lg-3" href="{{ route('login') }}">DAFTAR / MASUK</a>
            </div>
        </div>
    </nav>
    <!-- //NAVBAR -->

    <!-- HERO -->
    <div class="hero vh-100 d-flex align-items-center" id="home">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <h1 class="display-4 text-white">Ayo Segera Daftar</h1>
                    <p class="text-white my-3">
                        Mengelola penjurian setiap acara dengan mudah
                    </p>
                    <a href="{{ route('login') }}" class="btn me-2 btn-primary">Get Started</a>
                </div>
            </div>
        </div>
    </div>
    <!-- //HERO -->
    <!-- FEATURES -->
    <section class="row w-100 py-0 bg-light" id="features">
        <div class="col-lg-6 col-img"></div>
        <div class="col-lg-6 py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <h6 class="text-primary">FITUR</h6>
                        <h1>Solusi mudah untuk melakukan penjurian</h1>
                        <p>
                            Tidak perlu repot dalam mendata acara, peserta dan membuat
                            laporan hasil penjurian
                        </p>

                        <div class="feature d-flex mt-5">
                            <div class="iconbox me-3">
                                <i class="bx bxs-comment-edit"></i>
                            </div>
                            <div>
                                <h5>Kelola Acara</h5>
                                <p>
                                    Mengelola acara dapat dilakukan dengan mudah
                                </p>
                            </div>
                        </div>
                        <div class="feature d-flex">
                            <div class="iconbox me-3">
                                <i class="bx bxs-user-circle"></i>
                            </div>
                            <div>
                                <h5>Kelola Lomba</h5>
                                <p>
                                    Mengelola lomba pada setiap acara
                                </p>
                            </div>
                        </div>
                        <div class="feature d-flex">
                            <div class="iconbox me-3">
                                <i class="bx bxs-download"></i>
                            </div>
                            <div>
                                <h5>Laporan</h5>
                                <p>
                                    Membuat laporan dengan mudah dalam bentuk pdf
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FEATURES -->

    <!-- CONTACT -->
    <section id="contact">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8 mx-auto text-center">
                    <h6 class="text-primary">HUBUNGI KAMI</h6>
                </div>
            </div>

            <form action="" class="row g-3 justify-content-center">
                <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Full Name" />
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Enter E-mail" />
                </div>
                <div class="col-md-10">
                    <input type="text" class="form-control" placeholder="Enter Subject" />
                </div>
                <div class="col-md-10">
                    <textarea name="" id="" cols="30" rows="5" class="form-control"
                        placeholder="Enter Message"></textarea>
                </div>
                <div class="col-md-10 d-grid">
                    <button class="btn btn-primary">Contact</button>
                </div>
            </form>
        </div>
    </section>
    <!-- CONTACT -->

    <footer>
        <div class="footer-top">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-4">
                        <h3 class="text-white">PENJURIAN</h3>
                    </div>
                    <div class="col-lg-2">
                        <h5 class="text-white">Brand</h5>
                        <ul class="list-unstyled">
                            <li><a href="#">About</a></li>
                            <li><a href="#">Features</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2">
                        <h5 class="text-white">More</h5>
                        <ul class="list-unstyled">
                            <li><a href="#">FAQ's</a></li>
                            <li><a href="#">Privacy & Policy</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <h5 class="text-white">Contact</h5>
                        <ul class="list-unstyled">
                            <li>Email: adminpenjurian@gmail.com</li>
                            <li>Phone: (603) 555-0123</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">Copyright © 2021 Penjurian</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('landing-page/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('landing-page/js/wow.js') }}"></script>
    <script>
        new WOW().init();
    </script>
</body>

</html>
