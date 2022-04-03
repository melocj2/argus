@extends('layouts.app')

@section('content')
<div class="background"><div class="darken">
    <div class="container">
        <div class="col-md-8">
            <div class="card">
                <img src="images/logoW.svg" alt="logo" class="logo" />

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-6">
                                <input id="email" type="email" class="auth @error('email') is-invalid @enderror" name="email" placeholder="Email" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <input id="password" type="password" class="auth @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="linkFlex">
                            <a class="authlink" href="{{ route('password.request') }}">
                                {{ __('forgot password') }}
                            </a>

                            <a class="authlink" href="{{ route('register') }}">
                                {{ __('new user') }}
                            </a>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="authbtn">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div></div>
@endsection
