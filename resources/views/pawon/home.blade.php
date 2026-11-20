@extends('pawon.layouts.app')

@section('title', 'Beranda')
@section('page', 'index.html')
@section('script', 'home')

@section('content')
    <section class="hero">
        <div>
            <h1>Dapur Jawa yang selalu menyambut Anda</h1>
            <p>Masakan rumahan, sajian prasmanan dan suasana yang membuat Anda betah berlama-lama. Datang bersama keluarga dan rasakan suasana desa yang tenang.</p>
            <a class="btn" href="{{ route('menu') }}">Lihat Menu</a>
            <a class="btn ghost" href="{{ route('booking') }}">Booking Meja</a>
        </div>
        <img src="{{ Vite::asset('resources/images/pawon/hero.jpg') }}" alt="Bangunan joglo Pawon Ratu Roso">
    </section>
    <section class="wrap">
        <h2 class="c">Tentang Kami</h2>
        <div class="about">
            <div>
                <img src="{{ Vite::asset('resources/images/pawon/about.jpg') }}" alt="Sajian prasmanan">
                <div class="stats">
                    <div><b>4.7</b>Penilaian Pelanggan</div>
                    <div><b>40+</b>Menu</div>
                </div>
            </div>
            <p class="lead">Pawon berarti dapur dalam bahasa Jawa, dan di sinilah rasa Jawa yang hangat kami jaga. Pawon Ratu Roso menyajikan sayur, lauk, dan jajanan rumahan dengan bumbu yang mengingatkan Anda pada masakan nenek. Rasakan kenikmatan sederhana yang membuat rindu untuk kembali.</p>
        </div>
    </section>
    <section class="wrap">
        <h2>Menu Favorit</h2>
        <div class="grid" id="favGrid"></div>
        <h2>Menu Baru</h2>
        <div class="grid" id="newGrid"></div>
        <p class="c">
            <a class="ulink" href="{{ route('menu') }}">Lihat semua menu</a>
        </p>
    </section>
    <section class="wrap bookband">
        <div>
            <h2 class="big">Booking Meja Anda</h2>
            <p>Pesan tempat hanya dalam hitungan detik. Setelah itu, tinggal datang, duduk, dan nikmati.</p>
            <a class="btn" href="{{ route('booking') }}">Booking Meja</a>
        </div>
        <img src="{{ Vite::asset('resources/images/pawon/gallery.jpg') }}" alt="Taman dan gazebo">
    </section>
@endsection
