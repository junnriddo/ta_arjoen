<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\FonnteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function callback(Request $request, FonnteService $fonnteService): JsonResponse
    {
        $payload = $request->all();

        Log::info('Midtrans webhook masuk', $payload);

        $transactionStatus = $payload['transaction_status'] ?? null;
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        $orderId = (string) ($payload['order_id'] ?? '');

        // Verifikasi signature callback Midtrans
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));
        if (empty($signatureKey) || !hash_equals($expectedSignature, $signatureKey)) {
            Log::warning('Midtrans callback signature invalid', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
            ]);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Format order_id saat generate: JUNE-{booking_id}-{timestamp}
        $parts = explode('-', $orderId);
        $bookingId = isset($parts[1]) ? (int) $parts[1] : 0;

        if ($bookingId <= 0) {
            Log::warning('Midtrans callback order_id invalid', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid order_id'], 400);
        }

        $booking = Booking::find($bookingId);

        if (!$booking) {
            Log::warning('Booking tidak ditemukan dari Midtrans callback', [
                'booking_id' => $bookingId,
                'order_id' => $orderId,
            ]);
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Mapping status Midtrans -> status internal
        if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
            $wasPaid = $booking->payment_status === 'paid';

            $booking->update([
                'payment_status' => 'paid',
                'status' => Booking::STATUS_APPROVED,
                'paid_at' => now(),
            ]);

            if (!$wasPaid) {
                $fonnteService->sendWhatsApp('Pembayaran booking berhasil, cek dashboard admin.');
            }

            Log::info('Midtrans callback success: booking updated and WA sent', [
                'booking_id' => $booking->id,
                'transaction_status' => $transactionStatus,
            ]);
        } elseif ($transactionStatus === 'pending') {
            $booking->update([
                'payment_status' => 'pending',
            ]);

            Log::info('Midtrans callback pending', [
                'booking_id' => $booking->id,
            ]);
        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'], true)) {
            $booking->update([
                'payment_status' => 'failed',
            ]);

            Log::info('Midtrans callback failed/cancelled', [
                'booking_id' => $booking->id,
                'transaction_status' => $transactionStatus,
            ]);
        } else {
            Log::info('Midtrans callback unhandled status', [
                'booking_id' => $booking->id,
                'transaction_status' => $transactionStatus,
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
