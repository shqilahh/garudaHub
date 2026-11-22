@extends('layouts.app')

@section('content')
<h2>{{ $project->title }}</h2>
<p>{{ $project->description }}</p>
<p>
    Klien: {{ $project->client->name }} |
    Budget: Rp{{ number_format($project->budget) }} |
    Status: {{ $project->status }}
</p>

{{-- Edit/Hapus hanya tampil untuk pemilik proyek --}}
@if(auth()->id() === $project->client_id)
    <a href="{{ route('projects.edit', $project) }}">Edit</a>
    <form method="POST" action="{{ route('projects.destroy', $project) }}" class="inline">
        @csrf
        @method('DELETE')
        <button>Hapus</button>
    </form>
@endif

<h3>Penawaran</h3>
@forelse($project->bids as $b)
    <div class="card">
        {{ $b->developer->name }}: Rp{{ number_format($b->price) }},
        {{ $b->duration_days }} hari [{{ $b->status }}]
        <p>{{ $b->proposal }}</p>

        @if(auth()->id() === $project->client_id && $project->status === 'open')
            <form method="POST" action="{{ route('bids.accept', $b) }}" class="inline">
                @csrf
                @method('PATCH')
                <button>Pilih pemenang</button>
            </form>
        @endif

        @if(auth()->id() === $b->developer_id)
            <form method="POST" action="{{ route('bids.destroy', $b) }}" class="inline">
                @csrf
                @method('DELETE')
                <button>Hapus penawaran</button>
            </form>
        @endif
    </div>
@empty
    <p>Belum ada penawaran.</p>
@endforelse

@if($project->status === 'open')
    @auth
        @if(auth()->user()->role === 'developer')
            <h3>Ajukan penawaran</h3>
            <form method="POST" action="{{ route('bids.store', $project) }}">
                @csrf
                <label>Harga (Rp)</label>
                <input type="number" name="price">

                <label>Durasi (hari)</label>
                <input type="number" name="duration_days">

                <label>Proposal</label>
                <textarea name="proposal"></textarea>

                <button>Kirim</button>
            </form>
        @endif
    @else
        <p><a href="{{ route('login') }}">Login</a> sebagai developer untuk mengajukan penawaran.</p>
    @endauth
@endif
@endsection
