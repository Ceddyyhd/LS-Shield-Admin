@extends('layouts.auth', ['title' => 'Reset Password'])

@section('content')
<div class="col-xl-12">
    <div class="card auth-card">
        <div class="card-body p-0">
            <div class="row align-items-center g-0">
                <div class="col-lg-6 d-none d-lg-inline-block border-end">
                    <div class="auth-page-sidebar">
                        <img src="/images/reset-password.svg" alt="auth" class="img-fluid" />
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-4">
                        <div class="mx-auto mb-4 text-center auth-logo">
                            <a href="{{ route('home') }}" class="logo-dark">
                                <img src="/images/logo-sm.png" height="30" class="me-1" alt="logo sm" />
                                <img src="/images/logo-dark.png" height="24" alt="logo dark" />
                            </a>

                            <a href="{{ route('home') }}" class="logo-light">
                                <img src="/images/logo-sm.png" height="30" class="me-1" alt="logo sm" />
                                <img src="/images/logo-light.png" height="24" alt="logo light" />
                            </a>
                        </div>
                        <h2 class="fw-bold text-center fs-18">
                            Reset Password
                        </h2>
                        <p class="text-muted text-center mt-1 mb-4">
                            Enter your new password below.
                        </p>

                        <div class="row justify-content-center">
                            <div class="col-12 col-md-8">
                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $request->token }}">
                                    <div class="mb-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" value="{{ $request->email ?? old('email') }}" required autofocus />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" id="password" name="password" class="form-control" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required />
                                    </div>
                                    <div class="mb-1 text-center d-grid">
                                        <button class="btn btn-primary" type="submit">
                                            Reset Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection