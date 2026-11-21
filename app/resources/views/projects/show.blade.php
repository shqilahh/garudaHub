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

        @if($project->status === 'open')
            <form method="POST" action="{{ route('bids.accept', $b) }}" style="display:inline">
                @csrf
                @method('PATCH')
                <button>Pilih pemenang</button>
            </form>
        @endif

        <form method="POST" action="{{ route('bids.destroy', $b) }}" style="display:inline">
            @csrf
            @method('DELETE')
            <button>Hapus</button>
        </form>
    </div>
@empty
    <p>Belum ada penawaran.</p>
@endforelse

@if($project->status === 'open')
    <h3>Ajukan penawaran</h3>
    <form method="POST" action="{{ route('bids.store', $project) }}">
        @csrf
        <label>Developer</label>
        <select name="developer_id">
            @foreach($developers as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
            @endforeach
        </select>

        <label>Harga (Rp)</label>
        <input type="number" name="price">

        <label>Durasi (hari)</label>
        <input type="number" name="duration_days">

        <label>Proposal</label>
        <textarea name="proposal"></textarea>

        <button>Kirim</button>
    </form>
@endif
@endsection
