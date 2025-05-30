@extends('layouts.app')

@section('title', 'Buyme Bargians')

@section('content')


    <style>
        @media (min-width: 471px) {
            .tt-loginpages-wrapper .tt-loginpages {
                width: 50vw !important;
            }
        }
    </style>

    <main id="tt-pageContent" class="tt-offset-none" style="margin-top: 50px">
        <div class="container">
            <div class="tt-loginpages-wrapper">
                <div class="tt-loginpages">
                    <a href="index.html" class="tt-block-title">
                        <img src="images/logo.png" alt="">
                        <div class="tt-title">
                            Welcome to Buyme Bargians
                        </div>
                        <div class="tt-description">
                            Join to unlock true power of community.
                        </div>
                    </a>
                    <x-validation-errors class="mb-4" />

                    <form method="POST" class="form-default" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label for="loginUserName">Name</label>
                            <input type="text" name="name" class="form-control" id="loginUserName"
                                placeholder="Enter your name" value="{{ old('name') }}">
                        </div>
                        <div class="form-group">
                            <label for="loginUserEmail">Email</label>
                            <input type="text" name="email" class="form-control" id="loginUserEmail"
                                placeholder="Enter your email" value="{{ old('email') }}">
                        </div>
                        <div class="form-group">
                            <label for="loginUserPassword">Password</label>
                            <input type="password" name="password" class="form-control" id="loginUserPassword"
                                placeholder="************">
                        </div>
                        <div class="form-group">
                            <label for="loginUserPassword">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" id="loginUserPassword"
                                placeholder="************">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary btn-block">Create my account</a>
                        </div>

                        <p>Already have an account? <a href="{{ route('login') }}" class="tt-underline">Login here</a>
                        </p>
                        <div class="tt-notes">
                            By signing up, signing in or continuing, I agree to
                            Buyme Bargians’s <a href="{{ route('pages') }}?tab=term_and_services" class="tt-underline">Terms
                                of Use</a> and <a href="{{ route('pages') }}?tab=privacy" class="tt-underline">Privacy
                                Policy.</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

@endsection
