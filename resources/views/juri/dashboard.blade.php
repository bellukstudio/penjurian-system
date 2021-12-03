<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('juri._partials.head')
    <title>Dashboard | Juri</title>
</head>

<body>
    @include('juri._partials.navbar')

    <div class="mt-5 d-flex justify-content-center">
        @yield('content')
    </div>

    @include('juri._partials.footer')
</body>

</html>
