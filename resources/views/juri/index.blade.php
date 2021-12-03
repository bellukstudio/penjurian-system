@extends('juri.dashboard')
@section('content')
    <div class="container-fluid text-center position-absolute top-50">
        <div class="form-inline justify-content-center">
            @if (session()->has('tokenFailed'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('tokenFailed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
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
        <form class="form-inline justify-content-center" method="POST" action="{{ route('dashboardJuri.validateToken') }}">
            @csrf
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" name="token" class="form-control" id="inputtoken" placeholder="Token Acara"
                    autocomplete="off" value="{{ old('token') }}">
            </div>
            <button type="submit" class="btn btn-primary mb-2">Validasi Token</button>
        </form>
    </div>

@endsection
