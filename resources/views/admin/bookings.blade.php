@extends('admin.layout')

@section('title', 'Pesanan')

@php
    // Label pendek untuk chip filter, urutan seperti di desain
    $chip = ['menunggu' => 'Menunggu', 'dikonfirmasi' => 'Diterima', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'];
@endphp

@section('content')
    <div class="head">
        <div>
            <h1>Pesanan</h1>
            <p class="sub">Tinjau pesanan, beri keputusan, lalu perbarui statusnya.</p>
        </div>
    </div>

    <section class="card tablecard">
        <div class="tools">
            <form method="GET" action="{{ route('admin.bookings.index') }}">
                @if ($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <input class="field" type="search" name="q" value="{{ $cari }}" placeholder="Cari nama atau kode pesanan" aria-label="Cari nama atau kode pesanan">
            </form>
            <nav class="chips" aria-label="Filter status">
                <a href="{{ route('admin.bookings.index', ['q' => $cari ?: null]) }}" @class(['on' => ! $status])>Semua {{ $jumlah->sum() }}</a>
                @foreach ($chip as $nilai => $label)
                    <a href="{{ route('admin.bookings.index', ['status' => $nilai, 'q' => $cari ?: null]) }}" @class(['on' => $status === $nilai])>
                        {{ $label }} {{ $jumlah[$nilai] ?? 0 }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="scroll">
            <table>
                <thead>
                    <tr><th>Kode</th><th>Pemesan</th><th>Acara</th><th>Jadwal</th><th>Tamu</th><th class="num">Total</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $b)
                        <tr>
                            <td><b>{{ $b->kode }}</b></td>
                            <td>{{ $b->user->nama }}<small>{{ $b->user->no_whatsapp }}</small></td>
                            <td>{{ $b->jenis_acara }}</td>
                            <td class="nowrap">{{ $b->tanggal->locale('id')->translatedFormat('j M Y') }}<small>{{ $b->jam_label }} WIB</small></td>
                            <td class="nowrap">{{ $b->jumlah_tamu }}</td>
                            <td class="num">@rupiah($b->total_harga)</td>
                            <td><span class="pill {{ $b->status }}">{{ $b->statusLabel() }}</span></td>
                            <td class="num">
                                <a @class(['btn sm', 'ghost' => $b->status !== 'menunggu'])
                                   href="{{ route('admin.bookings.index', request()->except('page') + ['pesanan' => $b->id]) }}">
                                    {{ $b->status === 'menunggu' ? 'Tinjau' : 'Detail' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="muted">Tidak ada pesanan yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bookings->hasPages())
            <nav class="pager" aria-label="Halaman">
                <span>@if ($bookings->previousPageUrl())<a class="btn sm ghost" href="{{ $bookings->previousPageUrl() }}">‹ Sebelumnya</a>@endif</span>
                <span class="muted">Halaman {{ $bookings->currentPage() }} dari {{ $bookings->lastPage() }}</span>
                <span>@if ($bookings->nextPageUrl())<a class="btn sm ghost" href="{{ $bookings->nextPageUrl() }}">Berikutnya ›</a>@endif</span>
            </nav>
        @endif
    </section>

    @if ($dipilih)
        @php($tutup = route('admin.bookings.index', request()->except('pesanan')))
        <a class="scrim" href="{{ $tutup }}" aria-label="Tutup detail"></a>
        <aside class="drawer" aria-label="Detail pesanan {{ $dipilih->kode }}">
            <div class="drawer-head">
                <div>
                    <h2>{{ $dipilih->kode }}</h2>
                    <p>{{ $dipilih->user->nama }}</p>
                    <span class="pill {{ $dipilih->status }}">{{ $dipilih->statusLabel() }}</span>
                </div>
                <a class="xbtn" href="{{ $tutup }}" aria-label="Tutup detail">✕</a>
            </div>

            <p class="label">Pemesan</p>
            <div class="box">
                <div class="r"><span>Nama</span><span>{{ $dipilih->user->nama }}</span></div>
                <div class="r"><span>WhatsApp</span><span>{{ $dipilih->user->no_whatsapp }}</span></div>
                <div class="r"><span>Email</span><span>{{ $dipilih->user->email }}</span></div>
            </div>

            <p class="label">Acara</p>
            <div class="box">
                <div class="r"><span>Jenis Acara</span><span>{{ $dipilih->jenis_acara }}</span></div>
                <div class="r"><span>Tanggal</span><span>{{ $dipilih->tanggal->locale('id')->translatedFormat('l, j F Y') }}</span></div>
                <div class="r"><span>Jam</span><span>{{ $dipilih->jam_label }} WIB</span></div>
                <div class="r"><span>Jumlah Tamu</span><span>{{ $dipilih->jumlah_tamu }}</span></div>
            </div>

            <p class="label">Menu ({{ $dipilih->items->sum('qty') }} porsi)</p>
            <div class="box">
                @foreach ($dipilih->items as $item)
                    <div class="r"><span>{{ $item->menu->nama_menu }} x{{ $item->qty }}</span><span>@rupiah($item->subtotal)</span></div>
                @endforeach
                <div class="r total"><span>Total</span><span>@rupiah($dipilih->total_harga)</span></div>
            </div>

            <p class="label">Pesanan lain di tanggal ini</p>
            <div class="box">
                @forelse ($tanggalSama as $lain)
                    <div class="r">
                        <span>{{ $lain->jam_label }} · {{ $lain->user->nama }} ({{ $lain->jumlah_tamu }})</span>
                        <span class="pill {{ $lain->status }}">{{ $lain->statusLabel() }}</span>
                    </div>
                @empty
                    <div class="r"><span>Belum ada pesanan lain di tanggal ini</span></div>
                @endforelse
            </div>
            <p class="note">Gunakan ini sebagai bahan pertimbangan ketersediaan meja sebelum memberi keputusan.</p>

            @if ($dipilih->status === 'ditolak')
                <p class="label">Alasan penolakan</p>
                <p class="reason">{{ $dipilih->alasan_penolakan }}</p>
            @endif

            @if ($dipilih->status === 'menunggu')
                @php($tolakTerbuka = $errors->has('alasan_penolakan'))
                <div class="decide" id="putuskan" @if ($tolakTerbuka) hidden @endif>
                    <button class="btn red" type="button" onclick="document.getElementById('putuskan').hidden=true;document.getElementById('formTolak').hidden=false;document.getElementById('alasan').focus()">Tolak</button>
                    <form method="POST" action="{{ route('admin.bookings.update', $dipilih) }}">
                        @csrf @method('PATCH')
                        <button class="btn green" name="aksi" value="terima">Terima Pesanan</button>
                    </form>
                </div>
                <form id="formTolak" method="POST" action="{{ route('admin.bookings.update', $dipilih) }}" @unless ($tolakTerbuka) hidden @endunless>
                    @csrf @method('PATCH')
                    <p class="label" style="text-transform:none;letter-spacing:0">Alasan penolakan (akan terlihat oleh pemesan)</p>
                    <textarea class="field" id="alasan" name="alasan_penolakan" rows="3" required
                              placeholder="Contoh: Meja belum tersedia pada tanggal dan jam tersebut">{{ old('alasan_penolakan') }}</textarea>
                    <div class="decide" style="margin-top:12px">
                        <button class="btn ghost" type="button" onclick="document.getElementById('formTolak').hidden=true;document.getElementById('putuskan').hidden=false">Batal</button>
                        <button class="btn pink" name="aksi" value="tolak">Konfirmasi Tolak</button>
                    </div>
                </form>
            @elseif ($dipilih->status === 'dikonfirmasi')
                <form class="stack" method="POST" action="{{ route('admin.bookings.update', $dipilih) }}">
                    @csrf @method('PATCH')
                    <button class="btn tan full" name="aksi" value="selesai">Tandai Selesai</button>
                </form>
                <p class="note" style="text-align:center">Bisa ditandai selesai setelah acara berlangsung.</p>
            @endif

            <div class="stack">
                <a class="btn wa full" target="_blank" rel="noopener"
                   href="{{ $dipilih->user->whatsappUrl("Halo {$dipilih->user->nama}, ini admin Pawon Ratu Roso tentang pesanan {$dipilih->kode}.") }}">
                    Hubungi Pemesan via WhatsApp
                </a>
            </div>
        </aside>
    @endif
@endsection
