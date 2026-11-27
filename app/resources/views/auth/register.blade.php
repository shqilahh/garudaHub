@extends('layouts.app')

@section('content')
<h2>Daftar</h2>
<form method="POST" action="{{ route('register') }}">
    @csrf
    <label>Nama</label>
    <input name="name" value="{{ old('name') }}">

    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}">

    <label>Password (min. 8 karakter)</label>
    <input type="password" name="password">

    <label>Ulangi password</label>
    <input type="password" name="password_confirmation">

    <label>Daftar sebagai</label>
    <select name="role">
        <option value="client">Klien (pasang proyek)</option>
        <option value="developer">Developer (menawar)</option>
    </select>

    <button>Daftar</button>
</form>
@endsection
