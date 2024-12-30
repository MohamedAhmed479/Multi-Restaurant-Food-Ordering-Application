@extends('client.dashboard')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-semibold"><span class="text-primary mx-2">InActive</span>Account</h1>
        <h4 class="text-uppercase">Sorry, This Account Is Inactive Now</h4>
        <div class="mt-5 text-center">
            <a class="btn btn-primary waves-effect waves-light" href="{{ route('client.dashboard') }}">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>

@endsection
