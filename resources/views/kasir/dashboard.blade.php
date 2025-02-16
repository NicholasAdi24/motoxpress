@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard Kasir</h1>
    <p>Selamat datang, {{ Auth::user()->name }}! Anda telah login sebagai Kasir.</p>
</div>
@endsection
