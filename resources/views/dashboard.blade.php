@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="hero">
        <h1>Merhaba, {{ $user->name }}</h1>
        <p>{{ $tenant->name ?? $tenant->id }} workspace’ine hoş geldiniz.</p>
    </section>

    <div class="grid">
        <div class="card">
            <h2>Organizasyon</h2>
            <strong>{{ $tenant->name ?? $tenant->id }}</strong>
        </div>
        <div class="card">
            <h2>Kod</h2>
            <strong>{{ $tenant->id }}</strong>
        </div>
        <div class="card">
            <h2>E-posta</h2>
            <strong>{{ $user->email }}</strong>
        </div>
        <div class="card">
            <h2>Veritabanı</h2>
            <strong>{{ $tenant->database()->getName() }}</strong>
        </div>
    </div>
@endsection
