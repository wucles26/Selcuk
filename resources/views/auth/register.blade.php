@extends('layouts.guest')

@section('title', 'Kayıt Ol')

@section('content')
    <h1>Organizasyon oluştur</h1>
    <p class="subtitle">Kayıt olduğunuzda size özel bir veritabanı açılır.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="field">
            <label for="company">Şirket / organizasyon adı</label>
            <input id="company" type="text" name="company" value="{{ old('company') }}" required autofocus>
            @error('company') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="tenant">Organizasyon kodu</label>
            <input id="tenant" type="text" name="tenant" value="{{ old('tenant') }}" required placeholder="ornek: acme">
            <p class="hint">Girişte kullanılacak benzersiz kod (ör. acme).</p>
            @error('tenant') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="name">Adınız</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <p class="error">{{ $message }}</p> @enderror
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

        <div class="field">
            <label for="password_confirmation">Şifre tekrar</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <button class="btn" type="submit">Kayıt ol</button>
    </form>

    <p class="footer-link">
        Zaten hesabınız var mı?
        <a href="{{ route('login') }}">Giriş yapın</a>
    </p>
@endsection
