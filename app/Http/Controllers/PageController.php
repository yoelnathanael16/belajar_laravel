<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Support\MenuCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(private readonly MenuCatalog $catalog)
    {
    }

    public function home(): View
    {
        return view('pawon.home', ['menu' => $this->catalog->all()]);
    }

    public function about(): View
    {
        return view('pawon.about');
    }

    public function menu(): View
    {
        return view('pawon.menu', ['menu' => $this->catalog->all()]);
    }

    public function booking(): View
    {
        if (! auth()->check()) {
            // Setelah masuk atau daftar dari halaman ini, pelanggan kembali ke form booking
            redirect()->setIntendedUrl(route('booking'));
        }

        return view('pawon.booking', ['menu' => $this->catalog->all()]);
    }

    public function contact(): View
    {
        return view('pawon.contact');
    }

    public function profile(Request $request): View
    {
        // Bentuk data sama dengan yang dibaca resources/js/pawon/profile.js
        $orders = $request->user()->bookings()->with('items.menu')->latest('id')->get()
            ->map(fn (Booking $b) => [
                'code' => $b->kode,
                'ev' => $b->jenis_acara,
                'g' => $b->jumlah_tamu,
                'date' => $b->tanggal->toDateString(),
                'time' => str_replace(':', '.', substr($b->jam, 0, 5)) . ' WIB',
                'items' => $b->items->map(fn ($i) => ['n' => $i->menu->nama_menu, 'q' => $i->qty])->all(),
                'total' => $b->total_harga,
                'status' => $b->statusLabel(),
                // Tab "Sedang diproses": belum diputuskan atau sudah diterima tapi acaranya belum lewat
                'open' => in_array($b->status, ['menunggu', 'dikonfirmasi'], true),
                'reason' => $b->alasan_penolakan,
            ]);

        return view('pawon.profile', ['menu' => $this->catalog->all(), 'orders' => $orders]);
    }
}
