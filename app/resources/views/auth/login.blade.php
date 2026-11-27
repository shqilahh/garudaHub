@extends('layouts.app')

@section('content')
<h2>Login</h2>
<form method="POST" action="{{ route('login') }}">
    @csrf
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}">

    <label>Password</label>
    <input type="password" name="password">

    <button>Masuk</button>
</form>
<p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
@endsection
