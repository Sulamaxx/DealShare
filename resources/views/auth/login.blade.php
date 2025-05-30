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
                    <a href="/" class="tt-block-title">
                        <img src="images/logo.png" alt="">
                        <div class="tt-title">
                            Welcome to Buyme Bargians
                        </div>
                        <div class="tt-description">
                            Log into your account to unlock true power of community.
                        </div>
                    </a>
                    <form class="form-default" method="POST" action="{{ route('login') }}">
                        @csrf

                        <x-validation-errors class="mb-4" />

                        @session('status')
                            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ $value }}
                            </div>
                        @endsession

                        <div class="form-group">
                            <label for="loginUserName">Username</label>
                            <input type="email" name="email" class="form-control" id="loginUserName"
                                placeholder="Enter your email" value="{{ old(key: 'email') }}">
                        </div>
                        <div class="form-group">
                            <label for="loginUserPassword">Password</label>
                            <input type="password" name="password" class="form-control" id="loginUserPassword"
                                placeholder="************">
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="remember_me" name="remember">
                                        <label for="remember_me">
                                            <span class="check"></span>
                                            <span class="box"></span>
                                            <span class="tt-text">Remember me</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="col ml-auto text-right">
                                    <a href="{{ route('password.request') }}" class="tt-underline">Forgot Password</a>
                                </div>
                            @endif

                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary btn-block">Log in</a>
                        </div>

                        <p>Don’t have an account? <a href="{{ route('register') }}" class="tt-underline">Signup here</a>
                        </p>
                        <div class="tt-notes">
                            By Logging in, signing in or continuing, I agree to
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
