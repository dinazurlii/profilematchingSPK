@extends('layouts.mainApp')

@if (!session()->has('user'))
    <script>window.location.href = '/login';</script>
@endif

@section('content')
<div class="welcome-card">
<h2>Selamat Datang, {{ session('user')->full_name }}</h2>
<p>Selamat datang di sistem monitoring performa Aigen Corp. Kelola dan pantau performa karyawan dengan mudah melalui dashboard yang modern dan intuitif.</p>
</div>
@endsection
