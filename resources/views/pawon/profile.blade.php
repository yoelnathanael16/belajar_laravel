@extends('pawon.layouts.app')

@section('title', 'Profil')
@section('page', 'profil.html')
@section('script', 'profile')

@section('content')
    @php($user = auth()->user())
    <section class="wrap prof">
        <aside class="card">
            <div class="avatar">{{ Str::upper(Str::substr($user->nama, 0, 1)) }}</div>
            <div>
                <h2>{{ $user->nama }}</h2>
                <p class="small">{{ $user->email }}</p>
            </div>
            <dl class="pinfo">
                <dt>Nomor WhatsApp</dt><dd>{{ $user->no_whatsapp }}</dd>
                <dt>Bergabung sejak</dt><dd>{{ $user->created_at->locale('id')->translatedFormat('F Y') }}</dd>
            </dl>
            <div class="mini">
                <div><b id="c1">0</b>Sedang diproses</div>
                <div><b id="c2">0</b>Total Pesanan</div>
            </div>
            <a class="btn" href="{{ route('booking') }}">Buat Booking Baru</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn ghost" type="submit">Keluar</button>
            </form>
        </aside>
        <div class="card">
            <h1>Pesanan Saya</h1>
            <p class="small">Status pesanan diperbarui setelah admin memberi keputusan lewat WhatsApp</p>
            <div class="tabs">
                <button class="on" data-t="p">Sedang diproses <span class="n" id="n1">0</span></button>
                <button data-t="h">Riwayat <span class="n" id="n2">0</span></button>
            </div>
            <div id="orders"></div>
        </div>
    </section>
    <script type="application/json" id="pawon-orders">@json($orders, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE)</script>
@endsection
