<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PluginManager
{
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * Send a notification via LINE Notify.
     * Requires a LINE Notify access token.
     *
     * @param string $message
     * @param string $accessToken
     * @return bool
     */
    public function sendLineNotification(string $message, string $accessToken): bool
    {
        $url = 'https://notify-api.line.me/api/notify';
        try {
            $response = $this->httpClient->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'message' => $message,
                ],
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Log::error('LINE Notify error: ' . $e->getMessage());
            return false;
        }
    }
}
