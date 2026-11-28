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
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label>Email
                <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email">
            </label>
            <label>Kata sandi
                <input name="password" type="password" required autocomplete="current-password">
            </label>
            @if ($errors->any())
                <p class="err" role="alert">{{ $errors->first() }}</p>
            @endif
            <button class="btn" type="submit">Masuk</button>
        </form>
        <p class="alt">Belum punya akun? <a href="{{ route('register') }}">Gabung sekarang</a></p>
    </section>
@endsection
