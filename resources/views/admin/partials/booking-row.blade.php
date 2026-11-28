<div class="row">
    <div>
        <b>{{ $b->user->nama }} · {{ $b->jenis_acara }}</b>
        <p class="small">{{ $b->tanggal->translatedFormat('j M Y') }}, {{ substr($b->jam, 0, 5) }} WIB · {{ $b->jumlah_tamu }}</p>
    </div>
    <a class="btn ghost" href="{{ route('admin.bookings.index', ['pesanan' => $b->id]) }}">{{ $tombol }}</a>
</div>
