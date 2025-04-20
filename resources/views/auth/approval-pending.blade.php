@extends('layouts.app')

@section('title', 'Approval Pending')

@section('content')
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <div class="card shadow-lg border-0 p-4 d-flex justify-content-center align-items-center"
            style="max-width: 500px; height: 60vh; width: 100%;">
            <div class="card-body text-center">
                {{-- <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#ffc107"
                        class="bi bi-hourglass-split" viewBox="0 0 16 16">
                        <path
                            d="M2 15.5a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 0-1H13v-.105a2.5 2.5 0 0 0-1.057-2.033L10.1 11l1.843-1.362A2.5 2.5 0 0 0 13 7.605V7h.5a.5.5 0 0 0 0-1H13v-.105a2.5 2.5 0 0 0-1.057-2.033L10.1 3l1.843-1.362A2.5 2.5 0 0 0 13 1.605V1h.5a.5.5 0 0 0 0-1h-11a.5.5 0 0 0 0 1H3v.105a2.5 2.5 0 0 0 1.057 2.033L5.9 3 4.057 4.362A2.5 2.5 0 0 0 3 6.895V7h-.5a.5.5 0 0 0 0 1H3v.105a2.5 2.5 0 0 0 1.057 2.033L5.9 11l-1.843 1.362A2.5 2.5 0 0 0 3 14.895V15H2.5a.5.5 0 0 0-.5.5z" />
                    </svg>
                </div> --}}
                <h4 class="card-title mb-3 text-warning">Thank you for registering!</h4>
                <p class="card-text text-muted">
                    Your account is currently <strong>pending approval</strong> by an admin.<br>
                    You will be notified once your account has been reviewed and approved.
                </p>
                <a href="{{ url('/') }}" class="btn btn-outline-warning mt-4">Return to Home</a>
            </div>
        </div>
    </div>
@endsection
