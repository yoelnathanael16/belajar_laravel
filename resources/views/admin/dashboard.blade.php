@extends('admin.layout')

@section('title', 'Ringkasan')

@section('content')
    <div class="head">
        <div>
            <h1>Ringkasan</h1>
            <p class="sub">{{ today()->locale('id')->translatedFormat('l, j F Y') }}</p>
        </div>
    </div>

    <div class="stats">
        @foreach ($kartu as [$judul, $angka, $keterangan])
            <div class="card stat"><span>{{ $judul }}</span><b>{{ $angka }}</b><span class="muted">{{ $keterangan }}</span></div>
        @endforeach
    </div>

    <div class="cols">
        <section class="card panel">
            <h2>Perlu ditindak lanjuti</h2>
            <p class="muted">Pesanan yang menunggu keputusan, diurutkan dari tanggal terdekat.</p>
            @forelse ($perluTindakan as $b)
                @include('admin.partials.booking-row', ['b' => $b, 'tombol' => 'Tinjau'])
            @empty
                <p class="muted">Tidak ada pesanan yang menunggu.</p>
            @endforelse

            @if ($sudahLewat->isNotEmpty())
                <p class="subhead">Acara sudah lewat, tinggal ditandai selesai:</p>
                @foreach ($sudahLewat as $b)
                    @include('admin.partials.booking-row', ['b' => $b, 'tombol' => 'Buka'])
                @endforeach
            @endif
        </section>

        <section class="card panel">
            <h2>Jadwal yang akan datang</h2>
            <p class="muted">Pesanan yang sudah diterima.</p>
            @forelse ($akanDatang as $b)
                @include('admin.partials.booking-row', ['b' => $b, 'tombol' => 'Buka'])
            @empty
                <p class="muted">Belum ada jadwal.</p>
            @endforelse
        </section>
    </div>
@endsection
