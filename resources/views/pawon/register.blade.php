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
        <form id="authForm" data-mode="daftar" novalidate>
            <label>Nama lengkap
                <input name="name" type="text" required autocomplete="name">
            </label>
            <label>Email
                <input name="email" type="email" required autocomplete="email">
            </label>
            <label>Kata sandi
                <input name="password" type="password" required autocomplete="new-password">
            </label>
            <p class="err" id="err" role="alert" hidden></p>
            <button class="btn" type="submit">Buat Akun</button>
        </form>
        <p class="alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
    </section>
@endsection
