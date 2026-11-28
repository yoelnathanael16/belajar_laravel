<div class="item">
    <div>
        <b>{{ $b->user->nama }} · {{ $b->jenis_acara }}</b>
        <span class="muted">{{ $b->tanggal->locale('id')->translatedFormat('j M Y') }}, {{ $b->jam_label }} WIB · {{ $b->jumlah_tamu }}</span>
    </div>
    <a @class(['btn sm', 'ghost' => $tombol !== 'Tinjau']) href="{{ route('admin.bookings.index', ['pesanan' => $b->id]) }}">{{ $tombol }}</a>
</div>
