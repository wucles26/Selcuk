@extends('layouts.app')

@section('title', 'Haber Kategorileri')

@section('content')
<section class="hero">
    <h1>Haber Kategorileri</h1>
    <p>Kategorilerinizi, SEO alanlarını ve içerik önizlemesini yönetin.</p>
</section>
@if (session('status')) <p class="notice">{{ session('status') }}</p> @endif
<p><a class="button" href="{{ route('news-categories.create') }}">+ Yeni kategori</a></p>
<div class="card">
    @forelse ($categories as $category)
        <article class="row">
            <div>
                <strong>{{ $category->title }}</strong>
                <small>/{{ $category->slug }} · {{ $category->seo_title ?: 'SEO başlığı yok' }}</small>
            </div>
            <div class="actions">
                <a class="button secondary" href="{{ route('news-categories.edit', $category) }}">Düzenle</a>
                <form method="POST" action="{{ route('news-categories.destroy', $category) }}" onsubmit="return confirm('Bu kategori silinsin mi?')">
                    @csrf @method('DELETE')
                    <button class="button danger" type="submit">Sil</button>
                </form>
            </div>
        </article>
    @empty
        <p>Henüz haber kategorisi yok.</p>
    @endforelse
    {{ $categories->links() }}
</div>
@endsection
