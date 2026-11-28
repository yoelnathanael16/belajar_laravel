@extends('admin.layout')

@section('title', 'Pesanan')

@section('content')
    <div class="head">
        <div>
            <h1>Pesanan</h1>
            <p class="small">Tinjau pesanan, beri keputusan, lalu hubungi pemesan lewat WhatsApp.</p>
        </div>
    </div>

    <div @class(['split' => $dipilih])>
        <section class="card">
            <form method="GET" action="{{ route('admin.bookings.index') }}">
                @if ($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <input type="search" name="q" value="{{ $cari }}" placeholder="Cari nama atau kode pesanan">
            </form>

            <nav class="tabs">
                <a href="{{ route('admin.bookings.index', ['q' => $cari ?: null]) }}" @class(['on' => ! $status])>Semua {{ $jumlah->sum() }}</a>
                @foreach (\App\Models\Booking::STATUS_LABEL as $nilai => $label)
                    <a href="{{ route('admin.bookings.index', ['status' => $nilai, 'q' => $cari ?: null]) }}" @class(['on' => $status === $nilai])>
                        {{ $label }} {{ $jumlah[$nilai] ?? 0 }}
                    </a>
                @endforeach
            </nav>

            <table>
                <thead>
                    <tr><th>Kode</th><th>Pemesan</th><th>Acara</th><th>Jadwal</th><th>Tamu</th><th>Total</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $b)
                        <tr>
                            <td>{{ $b->kode }}</td>
                            <td>{{ $b->user->nama }}<br><span class="small">{{ $b->user->no_whatsapp }}</span></td>
                            <td>{{ $b->jenis_acara }}</td>
                            <td>{{ $b->tanggal->translatedFormat('j M Y') }}<br><span class="small">{{ substr($b->jam, 0, 5) }} WIB</span></td>
                            <td>{{ $b->jumlah_tamu }}</td>
                            <td>Rp{{ number_format($b->total_harga, 0, ',', '.') }}</td>
                            <td><span class="pill {{ $b->status }}">{{ $b->statusLabel() }}</span></td>
                            <td>
                                <a class="btn {{ $b->status === 'menunggu' ? '' : 'ghost' }}"
                                   href="{{ route('admin.bookings.index', request()->except('page') + ['pesanan' => $b->id]) }}">
                                    {{ $b->status === 'menunggu' ? 'Tinjau' : 'Detail' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="small">Tidak ada pesanan yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if ($bookings->hasPages())
                <nav class="pager tabs" aria-label="Halaman">
                    @if ($bookings->previousPageUrl())<a href="{{ $bookings->previousPageUrl() }}">‹ Sebelumnya</a>@endif
                    <span class="small">Halaman {{ $bookings->currentPage() }} dari {{ $bookings->lastPage() }}</span>
                    @if ($bookings->nextPageUrl())<a href="{{ $bookings->nextPageUrl() }}">Berikutnya ›</a>@endif
                </nav>
            @endif
        </section>

        @if ($dipilih)
            <aside class="card detail">
                <div class="head">
                    <div>
                        <h2>{{ $dipilih->kode }}</h2>
                        <p class="small">{{ $dipilih->user->nama }}</p>
                        <span class="pill {{ $dipilih->status }}">{{ $dipilih->statusLabel() }}</span>
                    </div>
                    <a class="btn ghost" href="{{ route('admin.bookings.index', request()->except('pesanan')) }}" aria-label="Tutup detail">✕</a>
                </div>

                <h3>Pemesan</h3>
                <dl>
                    <dt>Nama</dt><dd>{{ $dipilih->user->nama }}</dd>
                    <dt>WhatsApp</dt><dd>{{ $dipilih->user->no_whatsapp }}</dd>
                    <dt>Email</dt><dd>{{ $dipilih->user->email }}</dd>
                </dl>

                <h3>Acara</h3>
                <dl>
                    <dt>Jenis acara</dt><dd>{{ $dipilih->jenis_acara }}</dd>
                    <dt>Tanggal</dt><dd>{{ $dipilih->tanggal->translatedFormat('l, j F Y') }}</dd>
                    <dt>Jam</dt><dd>{{ substr($dipilih->jam, 0, 5) }} WIB</dd>
                    <dt>Jumlah tamu</dt><dd>{{ $dipilih->jumlah_tamu }}</dd>
                </dl>

                <h3>Menu ({{ $dipilih->items->sum('qty') }} porsi)</h3>
                <dl>
                    @foreach ($dipilih->items as $item)
                        <dt>{{ $item->menu->nama_menu }} x{{ $item->qty }}</dt>
                        <dd>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</dd>
                    @endforeach
                    <dt><b>Total</b></dt><dd>Rp{{ number_format($dipilih->total_harga, 0, ',', '.') }}</dd>
                </dl>

                <h3>Pesanan lain di tanggal ini</h3>
                @forelse ($tanggalSama as $lain)
                    <div class="row">
                        <span class="small">{{ substr($lain->jam, 0, 5) }} · {{ $lain->user->nama }} ({{ $lain->jumlah_tamu }})</span>
                        <span class="pill {{ $lain->status }}">{{ $lain->statusLabel() }}</span>
                    </div>
                @empty
                    <p class="small">Belum ada pesanan lain di tanggal ini.</p>
                @endforelse

                @if ($dipilih->status === 'ditolak')
                    <h3>Alasan penolakan</h3>
                    <p class="reason">{{ $dipilih->alasan_penolakan }}</p>
                @endif

                <div class="stack">
                    @if ($dipilih->status === 'menunggu')
                        <form method="POST" action="{{ route('admin.bookings.update', $dipilih) }}">
                            @csrf @method('PATCH')
                            <button class="btn green full" name="aksi" value="terima">Terima Pesanan</button>
                        </form>
                        <form method="POST" action="{{ route('admin.bookings.update', $dipilih) }}" class="stack">
                            @csrf @method('PATCH')
                            <textarea name="alasan_penolakan" rows="2" required placeholder="Alasan penolakan, mis. meja penuh di jam tersebut">{{ old('alasan_penolakan') }}</textarea>
                            <button class="btn red full" name="aksi" value="tolak">Tolak</button>
                        </form>
                    @elseif ($dipilih->status === 'dikonfirmasi')
                        <form method="POST" action="{{ route('admin.bookings.update', $dipilih) }}">
                            @csrf @method('PATCH')
                            <button class="btn full" name="aksi" value="selesai">Tandai Selesai</button>
                        </form>
                        <p class="small">Bisa ditandai selesai setelah acara berlangsung.</p>
                    @endif
                    <a class="btn wa" target="_blank" rel="noopener"
                       href="{{ $dipilih->user->whatsappUrl("Halo {$dipilih->user->nama}, ini admin Pawon Ratu Roso tentang pesanan {$dipilih->kode}.") }}">
                        Hubungi Pemesan via WhatsApp
                    </a>
                </div>
            </aside>
        @endif
    </div>
@endsection
