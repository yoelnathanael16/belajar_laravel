@extends('pawon.layouts.app')

@section('title', 'Booking')
@section('page', 'booking.html')
@section('script', 'booking')

@section('content')
    <section class="wrap">
        @guest
            <div class="gate card">
                <h2>Akun Diperlukan Untuk Booking</h2>
                <p>Daftar hanya butuh waktu singkat, dan Anda bisa langsung memesan meja setelahnya.</p>
                <a class="btn" href="{{ route('register') }}">Daftar Sekarang</a>
                <a class="btn ghost" href="{{ route('login') }}">Sudah punya akun? Masuk</a>
                <a class="ulink" href="{{ route('home') }}">Kembali ke Beranda</a>
            </div>
        @else
            <div id="wiz" class="wiz card">
                <ol class="steps" id="steps"></ol>
                <div id="pane"></div>
                <div class="wizbar">
                    <button class="btn ghost" id="back">Kembali</button>
                    <span id="sum"></span>
                    <button class="btn" id="next">Lanjut</button>
                </div>
            </div>
        @endguest
    </section>
@endsection
