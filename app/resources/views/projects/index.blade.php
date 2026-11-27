@extends('layouts.app')

@section('content')
<h2>Daftar Proyek</h2>

<form method="GET" action="{{ route('projects.index') }}">
    <input name="q" value="{{ request('q') }}" placeholder="Cari proyek...">
</form>

@forelse($projects as $p)
    <div class="card">
        <a href="{{ route('projects.show', $p) }}"><b>{{ $p->title }}</b></a>
        <div>
            {{ $p->client->name }} | {{ $p->category }} |
            Rp{{ number_format($p->budget) }} |
            {{ $p->bids_count }} penawaran | {{ $p->status }}
        </div>
    </div>
@empty
    <p>Belum ada proyek.</p>
@endforelse
@endsection
