<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    // aksi admin => [status sekarang yang diizinkan, status baru]
    private const AKSI = [
        'terima' => ['menunggu', 'dikonfirmasi'],
        'tolak' => ['menunggu', 'ditolak'],
        'selesai' => ['dikonfirmasi', 'selesai'],
    ];

    public function index(Request $request): View
    {
        $status = $request->query('status');
        $cari = trim((string) $request->query('q'));

        $bookings = Booking::with('user')
            ->when(isset(Booking::STATUS_LABEL[$status]), fn ($q) => $q->where('status', $status))
            ->when($cari !== '', function ($q) use ($cari) {
                // "PRS-0014" atau "14" mencari kode pesanan, selain itu mencari nama pemesan
                if (preg_match('/^(PRS-?)?0*(\d+)$/i', $cari, $m)) {
                    return $q->whereKey((int) $m[2]);
                }

                return $q->whereHas('user', fn ($u) => $u->where('nama', 'like', "%{$cari}%"));
            })
            ->orderByDesc('tanggal')->orderByDesc('jam')
            ->paginate(20)->withQueryString();

        $dipilih = $request->filled('pesanan')
            ? Booking::with('user', 'items.menu')->find($request->integer('pesanan'))
            : null;

        return view('admin.bookings', [
            'bookings' => $bookings,
            'jumlah' => Booking::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'status' => $status,
            'cari' => $cari,
            'dipilih' => $dipilih,
            // Bantu admin melihat bentrok jadwal sebelum menerima pesanan
            'tanggalSama' => $dipilih
                ? Booking::with('user')->whereDate('tanggal', $dipilih->tanggal)->whereKeyNot($dipilih->id)
                    ->where('status', '!=', 'ditolak')->orderBy('jam')->get()
                : collect(),
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'aksi' => ['required', Rule::in(array_keys(self::AKSI))],
            'alasan_penolakan' => ['required_if:aksi,tolak', 'nullable', 'string', 'max:500'],
        ], ['alasan_penolakan.required_if' => 'Tulis alasan penolakan untuk pemesan.']);

        [$dari, $ke] = self::AKSI[$data['aksi']];

        // Update bersyarat: kalau admin lain sudah mengubah status lebih dulu, tidak ada baris yang berubah
        $berubah = Booking::whereKey($booking->id)->where('status', $dari)->update([
            'status' => $ke,
            'alasan_penolakan' => $ke === 'ditolak' ? $data['alasan_penolakan'] : null,
        ]);

        $kembali = redirect()->route('admin.bookings.index', ['pesanan' => $booking->id]);

        return $berubah
            ? $kembali->with('status', "Pesanan {$booking->kode} sekarang: " . Booking::STATUS_LABEL[$ke] . '.')
            : $kembali->withErrors(['aksi' => "Status pesanan {$booking->kode} sudah berubah. Periksa lagi sebelum melanjutkan."]);
    }
}
