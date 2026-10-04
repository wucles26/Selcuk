@extends('layouts.app')
@section('title', 'Yeni Haber Kategorisi')
@section('content')
<section class="hero"><h1>Yeni Haber Kategorisi</h1><p>Alanları doldurun.</p></section>
@if ($errors->any()) <div class="notice">@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
<div class="card"><form method="POST" action="{{ route('news-categories.store') }}" enctype="multipart/form-data">@include('news-categories.form')</form></div>
@endsection
