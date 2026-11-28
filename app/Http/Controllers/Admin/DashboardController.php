<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $diterima = Booking::with('user')->where('status', 'dikonfirmasi')->orderBy('tanggal')->orderBy('jam')->get();
        // Sudah diterima tapi tanggalnya lewat: tinggal ditandai selesai
        [$sudahLewat, $akanDatang] = $diterima->partition(fn (Booking $b) => $b->tanggal->lt(today()));
        $jumlah = Booking::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('admin.dashboard', [
            // [judul, angka, keterangan kecil] untuk 4 kartu di atas
            'kartu' => [
                ['Menunggu konfirmasi', $jumlah['menunggu'] ?? 0, 'perlu keputusan Anda'],
                ['Diterima, akan datang', $akanDatang->count(),
                    'total Rp' . number_format($akanDatang->sum('total_harga'), 0, ',', '.')],
                ['Selesai', $jumlah['selesai'] ?? 0, 'pesanan'],
                ['Ditolak', $jumlah['ditolak'] ?? 0, 'pesanan'],
            ],
            'perluTindakan' => Booking::with('user')->where('status', 'menunggu')->orderBy('tanggal')->orderBy('jam')->get(),
            'sudahLewat' => $sudahLewat,
            'akanDatang' => $akanDatang,
        ]);
    }
}
