@extends('pawon.layouts.app')

@section('title', 'Daftar')
@section('page', 'daftar.html')
@section('script', 'register')
@section('body_class', 'authpage')

@section('content')
    <section class="authbox">
        <p class="kick">Halaman daftar</p>
        <h1>Yuk, Gabung di Pawon Ratu Roso</h1>
        <p>Buat akun agar pesan meja jadi lebih mudah.</p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <label>Nama lengkap
                <input name="nama" type="text" value="{{ old('nama') }}" required autocomplete="name">
            </label>
            <label>Email
                <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email">
            </label>
            {{-- Dipakai admin untuk menghubungi pemesan lewat WhatsApp --}}
            <label>Nomor WhatsApp
                <input name="no_whatsapp" type="tel" value="{{ old('no_whatsapp') }}" required autocomplete="tel" placeholder="081234567890">
            </label>
            <label>Kata sandi
                <input name="password" type="password" required minlength="8" autocomplete="new-password">
            </label>
            @if ($errors->any())
                <p class="err" role="alert">{{ $errors->first() }}</p>
            @endif
            <button class="btn" type="submit">Buat Akun</button>
        </form>
        <p class="alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
    </section>
@endsection
