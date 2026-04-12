<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function sendWhatsApp(string $message, ?string $target = null): bool
    {
        $token = config('services.fonnte.token');
        $target = $this->normalizeNumber($target ?? config('services.fonnte.admin_number'));

        if (empty($token) || empty($target)) {
            Log::warning('Fonnte config belum lengkap.', [
                'has_token' => !empty($token),
                'has_target' => !empty($target),
            ]);
            return false;
        }

        try {
            $response = Http::timeout(15)->withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
            ]);

            Log::info('Fonnte response', [
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Gagal kirim WhatsApp via Fonnte.', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function normalizeNumber(?string $number): ?string
    {
        if (empty($number)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $number);

        if (empty($digits)) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        return $digits;
    }
}
