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
            'jumlah' => [
                'Menunggu konfirmasi' => $jumlah['menunggu'] ?? 0,
                'Diterima, akan datang' => $akanDatang->count(),
                'Selesai' => $jumlah['selesai'] ?? 0,
                'Ditolak' => $jumlah['ditolak'] ?? 0,
            ],
            'perluTindakan' => Booking::with('user')->where('status', 'menunggu')->orderBy('tanggal')->orderBy('jam')->get(),
            'sudahLewat' => $sudahLewat,
            'akanDatang' => $akanDatang,
        ]);
    }
}
