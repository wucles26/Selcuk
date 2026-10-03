@extends('layouts.guest')

@section('title', 'Giriş Yap')

@section('content')
    <h1>Giriş yap</h1>
    <p class="subtitle">Organizasyon kodunuz ve hesabınızla devam edin.</p>

    @if (session('error'))
        <p class="error" style="margin-bottom: 1rem;">{{ session('error') }}</p>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="field">
            <label for="tenant">Organizasyon kodu</label>
            <input id="tenant" type="text" name="tenant" value="{{ old('tenant') }}" required autofocus placeholder="ornek: acme">
            @error('tenant') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="email">E-posta</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password">Şifre</label>
            <input id="password" type="password" name="password" required>
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <label class="remember">
            <input type="checkbox" name="remember">
            Beni hatırla
        </label>

        <button class="btn" type="submit">Giriş yap</button>
    </form>

    <p class="footer-link">
        Hesabınız yok mu?
        <a href="{{ route('register') }}">Kayıt olun</a>
    </p>
@endsection
