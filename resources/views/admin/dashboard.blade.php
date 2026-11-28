@extends('admin.layout')

@section('title', 'Ringkasan')

@section('content')
    <div class="head">
        <div>
            <h1>Ringkasan</h1>
            <p class="small">{{ today()->translatedFormat('l, j F Y') }}</p>
        </div>
    </div>

    <div class="grid4">
        @foreach ($jumlah as $label => $angka)
            <div class="card"><span class="small">{{ $label }}</span><b>{{ $angka }}</b></div>
        @endforeach
    </div>

    <div class="grid2">
        <section class="card">
            <h2>Perlu ditindaklanjuti</h2>
            <p class="small">Pesanan yang menunggu keputusan, diurutkan dari tanggal terdekat</p>
            @forelse ($perluTindakan as $b)
                @include('admin.partials.booking-row', ['b' => $b, 'tombol' => 'Tinjau'])
            @empty
                <p class="small">Tidak ada pesanan yang menunggu.</p>
            @endforelse

            @if ($sudahLewat->isNotEmpty())
                <h3 class="small" style="margin-top:16px">Acara sudah lewat, tinggal ditandai selesai</h3>
                @foreach ($sudahLewat as $b)
                    @include('admin.partials.booking-row', ['b' => $b, 'tombol' => 'Buka'])
                @endforeach
            @endif
        </section>

        <section class="card">
            <h2>Jadwal yang akan datang</h2>
            <p class="small">Pesanan yang sudah diterima</p>
            @forelse ($akanDatang as $b)
                @include('admin.partials.booking-row', ['b' => $b, 'tombol' => 'Buka'])
            @empty
                <p class="small">Belum ada jadwal.</p>
            @endforelse
        </section>
    </div>
@endsection
