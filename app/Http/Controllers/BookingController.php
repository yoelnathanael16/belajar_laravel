<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    /**
     * Simpan booking dari form 5 langkah (resources/js/pawon/booking.js).
     * Harga selalu diambil dari database, bukan dari browser.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'jenis_acara' => ['required', Rule::in(Booking::JENIS_ACARA)],
            'jumlah_tamu' => ['required', Rule::in(Booking::JUMLAH_TAMU)],
            'tanggal' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'jam' => ['required', Rule::in(Booking::JAM)],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'string', 'distinct', Rule::exists('menus', 'slug')->where('tersedia', true)],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:500'],
        ], [
            'tanggal.after_or_equal' => 'Tanggal booking tidak boleh sebelum hari ini.',
            'items.required' => 'Pilih minimal satu menu.',
            'items.*.id.exists' => 'Ada menu yang sudah tidak tersedia. Muat ulang halaman lalu pilih lagi.',
        ]);

        $menus = Menu::whereIn('slug', array_column($data['items'], 'id'))->get()->keyBy('slug');

        $items = array_map(fn (array $item) => [
            'menu_id' => $menus[$item['id']]->id,
            'qty' => $item['qty'],
            'harga_satuan' => $menus[$item['id']]->harga,
            'subtotal' => $menus[$item['id']]->harga * $item['qty'],
        ], $data['items']);

        // Satu transaksi: booking tidak pernah tersimpan tanpa itemnya
        $booking = DB::transaction(function () use ($request, $data, $items) {
            $booking = $request->user()->bookings()->create([
                'jenis_acara' => $data['jenis_acara'],
                'jumlah_tamu' => $data['jumlah_tamu'],
                'tanggal' => $data['tanggal'],
                'jam' => $data['jam'],
                'total_harga' => array_sum(array_column($items, 'subtotal')),
            ]);
            $booking->items()->createMany($items);

            return $booking;
        });

        return response()->json(['kode' => $booking->kode, 'total' => $booking->total_harga], 201);
    }
}
