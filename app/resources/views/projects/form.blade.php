@extends('layouts.app')

@section('content')
<h2>{{ $project->exists ? 'Edit Proyek' : 'Proyek Baru' }}</h2>

<form method="POST" action="{{ $project->exists ? route('projects.update', $project) : route('projects.store') }}">
    @csrf
    @if($project->exists)
        @method('PUT')
    @endif

    <label>Judul</label>
    <input name="title" value="{{ old('title', $project->title) }}">

    <label>Deskripsi</label>
    <textarea name="description">{{ old('description', $project->description) }}</textarea>

    <label>Kategori</label>
    <input name="category" value="{{ old('category', $project->category) }}">

    <label>Budget (Rp)</label>
    <input type="number" name="budget" value="{{ old('budget', $project->budget) }}">

    <label>Batas lelang</label>
    <input type="datetime-local" name="bid_deadline"
           value="{{ old('bid_deadline', optional($project->bid_deadline)->format('Y-m-d\TH:i')) }}">

    <button>Simpan</button>
</form>
@endsection
