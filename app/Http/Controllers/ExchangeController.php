<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class ExchangeController extends Controller
{
    private static $instance;
    private $exchangeData;

    private function __construct()
    {
        // Private constructor to prevent instantiation
        $this->fetchExchangeData();
    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $baseCurrency
     *
     * @return void
     */
    private function fetchExchangeData($baseCurrency = 'USD')
    {
        try {
            $response = Http::get('https://cb.am/latest.json.php', ['currency' => $baseCurrency]);

            if ($response->successful() && $response->json()) {
                $this->exchangeData = $response->json();
            } else {
                throw new Exception('Received status code ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error fetching exchange data: ' . $e->getMessage());
        }
    }

    /**
     * @param $fromCurrency
     *
     * @return float|int
     */
    public function getExchangeRate($fromCurrency)
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

        $exchangeRate = $this->getExchangeRate($fromCurrency);

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
     * @return string
     */
    public function needToConvert(): string
    {
        $currentUserRole = auth()->user()->role->type ?? '';

        return $currentUserRole !== 'superadmin' && $currentUserRole !== 'admin';
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


    /**
     * @throws Exception to prevent cloning object.
     */
    public function __clone()
    {
        throw new Exception('You cannot clone singleton object');
    }
}
