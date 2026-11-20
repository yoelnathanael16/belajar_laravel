@extends('pawon.layouts.app')

@section('title', 'Profil')
@section('page', 'profil.html')
@section('script', 'profile')

@section('content')
    <section class="wrap prof" id="profRoot" hidden>
        <aside class="card">
            <div class="avatar" id="av"></div>
            <h2 id="pn"></h2>
            <p id="pe" class="small"></p>
            <div class="mini">
                <div><b id="c1">0</b>Sedang diproses</div>
                <div><b id="c2">0</b>Total Pesanan</div>
            </div>
            <a class="btn" href="{{ route('booking') }}">Buat Booking Baru</a>
            <button class="btn ghost" id="logout">Keluar</button>
        </aside>
        <div class="card">
            <h1>Pesanan Saya</h1>
            <p class="small">Status pesanan diperbarui setelah admin memberi keputusan lewat WhatsApp</p>
            <div class="tabs">
                <button class="on" data-t="p">Sedang diproses</button>
                <button data-t="h">Riwayat</button>
            </div>
            <div id="orders"></div>
        </div>
    </section>
@endsection
