@extends('pawon.layouts.app')

@section('title', 'Masuk')
@section('page', 'masuk.html')
@section('script', 'login')
@section('body_class', 'authpage')

@section('content')
    <section class="authbox">
        <p class="kick">Halaman masuk</p>
        <h1>Selamat Datang Kembali</h1>
        <p>Senang bertemu Anda lagi. Masuk untuk melanjutkan.</p>
        <form id="authForm" data-mode="masuk" novalidate>
            <label>Email
                <input name="email" type="email" required autocomplete="email">
            </label>
            <label>Kata sandi
                <input name="password" type="password" required autocomplete="current-password">
            </label>
            <p class="err" id="err" role="alert" hidden></p>
            <button class="btn" type="submit">Masuk</button>
        </form>
        <p class="alt">Belum punya akun? <a href="{{ route('register') }}">Gabung sekarang</a></p>
    </section>
@endsection
