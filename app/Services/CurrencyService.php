<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    private const FALLBACK_RATE = 24.60;
    
    public function getUsdToHnlRate(): float
    {
        return Cache::remember('usd_to_hnl_rate', 43200, function () {
            try {
                $response = Http::timeout(5)->get('https://open.er-api.com/v6/latest/USD');
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['rates']['usd_to_hnl_rate'])) {
                        return (float) $data['rates']['HNL'];
                    }
                }
                
                Log::warning('Failed to fetch exchange rate from API, using fallback.', ['response' => $response->body() ?? null]);
            } catch (\Exception $e) {
                Log::error('Exception while fetching exchange rate: ' . $e->getMessage());
            }

            return self::FALLBACK_RATE;
        });
    }

    public function convertToHnl(float $amount, string $fromCurrency): float
    {
        if ($fromCurrency === 'HNL') {
            return $amount;
        }

        if ($fromCurrency === 'USD') {
            $rate = $this->getUsdToHnlRate();
            return $amount * $rate;
        }

        return $amount;
    }
}
