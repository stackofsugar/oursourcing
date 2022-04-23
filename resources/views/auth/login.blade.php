@extends('layouts.main')

@section('title', 'Login')

@section('content')
    <div class="container">
        <div class="row">
            <h1 class="mt-2">Login</h1>
        </div>
        <div>
            <a href="/">Kembali ke Home</a>
        </div>
        @if (session('loginError'))
            <div class="row alert alert-danger mt-4" role="alert">
                <div><strong>Login Gagal</strong></div>
                <div>{{ session('loginError') }}</div>
            </div>
        @endif
        <form action="{{ route('login') }}" method="POST" class="row">
            @csrf
            <div class="col-md-6 col-12 mt-3">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                        name="username" value="{{ old('username') }}" required autofocus>
                    @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" required>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat Saya</label>
                </div>
                <div class="mb-3">
                    <a href="#!">Lupa Password</a>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </div>
        </form>
        <div class="mt-3">
            <p>Belum memiliki akun? Silakan <a href="{{ route('register') }}">daftar</a></p>
        </div>
    </div>
@endsection
