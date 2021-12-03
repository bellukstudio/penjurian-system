<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('user.__partials.head')
    <title>Dashboard | User</title>
</head>

<body class="text-center">
    @include('user.__partials.navbar')


    <div class="container-fluid mt-5">
        @yield('content')
    </div>

    @include('user.__partials.footer')
</body>

</html>
