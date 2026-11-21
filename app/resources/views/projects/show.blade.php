@extends('layouts.app')

@section('content')
<h2>{{ $project->title }}</h2>
<p>{{ $project->description }}</p>
<p>
    Klien: {{ $project->client->name }} |
    Budget: Rp{{ number_format($project->budget) }} |
    Status: {{ $project->status }}
</p>

<a href="{{ route('projects.edit', $project) }}">Edit</a>
<form method="POST" action="{{ route('projects.destroy', $project) }}" style="display:inline">
    @csrf
    @method('DELETE')
    <button>Hapus</button>
</form>

<h3>Penawaran</h3>
@forelse($project->bids as $b)
    <div class="card">
        {{ $b->developer->name }}: Rp{{ number_format($b->price) }},
        {{ $b->duration_days }} hari [{{ $b->status }}]
        <p>{{ $b->proposal }}</p>
    </div>
@empty
    <p>Belum ada penawaran.</p>
@endforelse
@endsection
