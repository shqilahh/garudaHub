<!DOCTYPE html>
{{-- NIM: 252410102022 --}}
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Garuda-Hub</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
<nav>
    <a href="{{ route('projects.index') }}">Garuda-Hub</a>

    @auth
        @if(auth()->user()->role === 'client')
            | <a href="{{ route('projects.create') }}">+ Proyek Baru</a>
        @endif
        | Halo, {{ auth()->user()->name }} ({{ auth()->user()->role }})
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button>Logout</button>
        </form>
    @else
        | <a href="{{ route('login') }}">Login</a>
        | <a href="{{ route('register') }}">Daftar</a>
    @endauth
</nav>

@if(session('ok'))
    <p class="ok">{{ session('ok') }}</p>
@endif

@if($errors->any())
    <ul class="err">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
@endif

@yield('content')
</body>
</html>
