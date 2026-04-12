<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans config
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    /**
     * Halaman pembayaran - tampilkan Snap Midtrans
     */
    public function pay(Booking $booking)
    {
        $booking->load('lapangan');

        // Jika sudah bayar, redirect ke sukses
        if ($booking->payment_status === 'paid') {
            return redirect()->route('booking.sukses', $booking->id)
                ->with('success', 'Pembayaran sudah berhasil sebelumnya.');
        }

        // Buat snap token jika belum ada
        if (empty($booking->snap_token)) {
            try {
                $params = [
                    'transaction_details' => [
                        'order_id'     => 'JUNE-' . $booking->id . '-' . time(),
                        'gross_amount' => (int) $booking->harga,
                    ],
                    'customer_details' => [
                        'first_name' => $booking->nama_pelanggan,
                        'phone'      => $booking->no_hp,
                    ],
                    'item_details' => [
                        [
                            'id'       => $booking->lapangan_id,
                            'price'    => (int) $booking->harga,
                            'quantity' => 1,
                            'name'     => $booking->lapangan->nama_lapangan . ' - ' . $booking->jam . ' (' . $booking->tanggal . ')',
                        ],
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);
                $booking->update(['snap_token' => $snapToken]);
            } catch (\Throwable $e) {
                Log::error('Gagal membuat Snap Token Midtrans', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);

                return back()->with('error', 'Gagal membuat transaksi pembayaran. Silakan coba lagi.');
            }
        }

        return view('booking.payment', compact('booking'));
    }

    /**
     * Callback dari Midtrans (frontend) — update status pembayaran
     */
    public function callback(Request $request, FonnteService $fonnteService)
    {
        try {
            $booking = Booking::findOrFail($request->booking_id);
            $wasPaid = $booking->payment_status === 'paid';

            // Callback frontend dari Snap (webhook resmi tetap di /midtrans/callback)
            $status = $request->transaction_status;

            if ($status === 'capture' || $status === 'settlement') {
                $booking->update([
                    'payment_status' => 'paid',
                    'status'         => Booking::STATUS_APPROVED,
                    'paid_at'        => now(),
                ]);

                if (!$wasPaid) {
                    $fonnteService->sendWhatsApp(
                        'Pembayaran booking berhasil, cek dashboard admin.'
                    );
                }

                return response()->json(['message' => 'Pembayaran berhasil!']);
            }

            if ($status === 'pending') {
                $booking->update(['payment_status' => 'pending']);
                return response()->json(['message' => 'Menunggu pembayaran.']);
            }

            // Denied, expire, cancel
            $booking->update(['payment_status' => 'failed']);
            return response()->json(['message' => 'Pembayaran gagal.']);
        } catch (\Throwable $e) {
            Log::error('Error callback frontend Midtrans', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Terjadi kesalahan callback.'], 500);
        }
    }
}
