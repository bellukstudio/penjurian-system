@extends('user.dashboard')
@section('content')
    <div class="container text-start">
        @if (session()->has('juriFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('juriFailed') }}
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
        <form action="{{ route('manageJuri.store') }}" method="POST" class="mb-5">
            @csrf
            <div class="mb-3">
                <label for="exampleInputFullName" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="exampleInputFullName" name="name" value="{{ old('name') }}"
                    placeholder="Jhon Doe">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">AlamatEmail</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email"
                    value="{{ old('email') }}" placeholder="jhondoe@gmail.com">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password"
                    placeholder="Password">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <br><br><br>

@endsection
