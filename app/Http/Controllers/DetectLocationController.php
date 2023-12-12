<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class DetectLocationController extends Controller
{
    private static $instance;
    private $locationInfo;
    public $countryCode;

    private function __construct()
    {
        $this->fetchLocationInfo();

        $this->countryCode = $this->locationInfo['country_code'] ?? 'AM';
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
     * @throws Exception to prevent cloning object.
     */
    public function __clone()
    {
        throw new Exception('You cannot clone singleton object');
    }

    public function fetchLocationInfo()
    {
        try {
            $ipAddr = $this->getIPAddress();

            $response = Http::get("https://ipwho.is/$ipAddr");

            if ($response->successful() && $response->json()) {
                $this->locationInfo = $response->json();
            } else {
                throw new Exception('Received status code ' . $response->status());
            }
        } catch (Exception $e) {
            Log::error('Error fetching exchange data: ' . $e->getMessage());
        }
    }

    /**
     * @return string
     */
    private function getIPAddress(): string
    {
        if ( ! empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * @return mixed|string
     */
    public function getCountryCode(){
        return $this->countryCode;
    }
}
