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

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


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


    public function getExchangeRate($toCurrency)
    {
        return ! empty($this->exchangeData[$toCurrency]) ? (float)$this->exchangeData[$toCurrency] : 0;
    }

    public function convertPrice($amount, $toCurrency)
    {
        $exchangeRate = $this->getExchangeRate($toCurrency);

        if ($exchangeRate) {
            if ($exchangeRate > 0) {
                $convertedPrice = ($amount * $exchangeRate);
            } else {
                $convertedPrice = ceil($amount / $exchangeRate);
            }

//            var_dump($amount);
//            var_dump($exchangeRate);
//            var_dump($convertedPrice);
//            exit;

            return ['converted_price' => $convertedPrice, 'currency' => $toCurrency];
        } else {
            $amount;
        }
    }

    /**
     * @throws Exception to prevent cloning object.
     */
    public function __clone()
    {
        throw new Exception('You cannot clone singleton object');
    }
}
