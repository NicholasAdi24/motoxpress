@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, {{ Auth::user()->name }}! Anda telah login sebagai Admin.</p>
</div>
@endsection
