@extends('layouts.app')
@section('title', 'Kategori Düzenle')
@section('content')
<section class="hero"><h1>Kategori Düzenle</h1><p>{{ $category->title }}</p></section>
@if ($errors->any()) <div class="notice">@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
<div class="card"><form method="POST" action="{{ route('news-categories.update', $category) }}" enctype="multipart/form-data">@method('PUT') @include('news-categories.form')</form></div>
@endsection
