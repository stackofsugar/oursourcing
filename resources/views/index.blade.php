@extends('layouts.main')

@section('title', 'Home')

@section('content')
    <div class="container">
        <div class="mt-2">
            <h1>OurSourcing</h1>
        </div>
        @auth
            <div>
                <span>Hai, <strong>{{ Auth::user()->fullname }}</strong></span>
            </div>
            <div>
                <a href="/logout">Logout</a>
            </div>
        @else
            <div>
                <a href="/register" class="me-2">Daftar</a>
                <a href="/login">Masuk</a>
            </div>
        @endauth
    </div>
    {{-- {{ dd(App\Models\User::first()) }} --}}
@endsection
