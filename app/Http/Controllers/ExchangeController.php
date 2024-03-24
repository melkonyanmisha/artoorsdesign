<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class ExchangeController extends Controller
{
    private static $instance;
    private array $exchangeData = [];

    private function __construct()
    {
        $this->fetchExchangeData();
    }

    /**
     * @throws Exception to prevent cloning object.
     */
    public function __clone()
    {
        throw new Exception('You cannot clone singleton object');
    }

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $currency
     *
     * @return void
     */
    private function fetchExchangeData(string $currency = 'USD'): void
    {
        try {
            $response = Http::get('https://cb.am/latest.json.php', ['currency' => $currency]);

            if ($response->successful() && $response->json()) {
                $this->exchangeData = $response->json();
            } else {
                throw new Exception('Received status code ' . $response->status());
            }
        } catch (Exception $e) {
            Log::error('Error fetching exchange data: ' . $e->getMessage());
        }
    }

    /**
     * @param $fromCurrency
     *
     * @return float|int
     */
    public function getExchangeRate($fromCurrency): float|int
    {
        return ! empty($this->exchangeData[$fromCurrency]) ? (float)$this->exchangeData[$fromCurrency] : 0;
    }

    /**
     * @param float $amount
     * @param string $fromCurrency
     *
     * @return array
     */
    public function convertPriceToAMD(float $amount, string $fromCurrency): array
    {
        $convertedPrice = $amount;
        $exchangeRate   = $this->getExchangeRate($fromCurrency);

        if ($exchangeRate) {
            if ($exchangeRate > 1) {
                $convertedPrice = ceil($amount * $exchangeRate);
            } else {
                $convertedPrice = $exchangeRate == 0 ? ceil($amount / $exchangeRate) : $amount;
            }
        }

        return ['price' => $convertedPrice, 'currency' => $fromCurrency];
    }

    /**
     * @return bool
     */
    public function needToConvert(): bool
    {
        // The case when can't fetch the exchange data
        if (empty($this->exchangeData)) {
            return false;
        }

        $countryCode     = DetectLocationController::getInstance()->getCountryCode();
        $currentUserRole = auth()->user()->role->type ?? '';
        if ($countryCode === 'AM' && $currentUserRole !== 'superadmin' && $currentUserRole !== 'admin') {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getUSDSymbol(): string
    {
        return '$';
    }

    /**
     * @return string
     */
    public function getAMDSymbol(): string
    {
        return '֏';
    }
}
