@extends('user.dashboard')
@section('content')
    <div class=" d-flex justify-content-center">
        <div class="card border-primary mb-3 mr-5 position-relative top-50" style="max-width: 18rem;">
            <div class="card-header">Data Event</div>
            <div class="card-body text-primary">
                <h5 class="card-title">Acara yang pernah dibuat</h5>
                <h6 class="card-body text-primary">{{ $event }}</h6>
            </div>
        </div>
        <div class="card border-primary mb-3 position-relative top-50" style="max-width: 18rem;">
            <div class="card-header">Data Juri</div>
            <div class="card-body text-primary">
                <h5 class="card-title">Juri yang pernah didaftarkan</h5>
                <h6 class="card-body text-primary">{{ $jury }}</h6>
            </div>
        </div>
    </div>
@endsection
