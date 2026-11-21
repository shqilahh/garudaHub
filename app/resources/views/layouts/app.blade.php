<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Garuda-Hub</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
<nav>
    <a href="{{ route('projects.index') }}">Garuda-Hub</a> |
    <a href="{{ route('projects.create') }}">+ Proyek Baru</a>
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
