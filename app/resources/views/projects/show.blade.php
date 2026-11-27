@extends('layouts.app')

@section('content')
<h2>{{ $project->title }}</h2>
<p>{{ $project->description }}</p>
<p>
    Klien: {{ $project->client->name }} |
    Budget: Rp{{ number_format($project->budget) }} |
    Status: {{ $project->status }}
</p>

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
    @php
        $total = $b->developer->ratingsReceived()->count();
        $avg   = $b->developer->ratingsReceived()->avg('score');
    @endphp
    <div class="card">
        {{ $b->developer->name }}: Rp{{ number_format($b->price) }},
        {{ $b->duration_days }} hari [{{ $b->status }}]
        <br>
        <small>Reputasi developer: {{ $total ? number_format($avg, 1).' / 5 ('.$total.' rating)' : 'belum ada rating' }}</small>
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

        {{-- Rating hanya untuk penawaran pemenang --}}
        @if($b->status === 'accepted')
            @if($b->rating)
                <p><b>Rating: {{ $b->rating->score }}/5</b> {{ $b->rating->comment }}</p>
            @elseif(auth()->id() === $project->client_id)
                <form method="POST" action="{{ route('ratings.store', $b) }}">
                    @csrf
                    <label>Beri rating developer</label>
                    <select name="score">
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <textarea name="comment" placeholder="Komentar (opsional)"></textarea>
                    <button>Kirim rating</button>
                </form>
            @endif
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
