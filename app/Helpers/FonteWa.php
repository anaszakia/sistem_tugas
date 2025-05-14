<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonteWa
{
    public static function send($target, $message)
    {
        try {
            $apiKey = env('FONTE_API_TOKEN');

            $response = Http::withHeaders([
                'Authorization' => $apiKey,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
            ]);

            $responseData = $response->json();

            Log::debug('Respons dari Fonnte', $responseData);

            if ($response->successful() && isset($responseData['status']) && $responseData['status'] === true) {
                return true;
            }

            Log::error('Gagal kirim WA', $responseData);
            return false;
        } catch (\Exception $e) {
            Log::error('Exception saat kirim WA: ' . $e->getMessage());
            return false;
        }
    }
}
