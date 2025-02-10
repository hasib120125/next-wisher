<?php
namespace App;

use Exception;
use GuzzleHttp\Client;
use Google\Auth\CredentialsLoader;

class PushNotification {
    public static function getAccessToken()
    {
        $credentialsPath = storage_path('firebase/firebase_credentials.json');

        $auth = CredentialsLoader::makeCredentials(
            ['https://www.googleapis.com/auth/cloud-platform'],
            json_decode(file_get_contents($credentialsPath), true)
        );

        $auth->fetchAuthToken();
        return $auth->getLastReceivedToken()['access_token'];
    }
    public static function sendPushNotification($deviceToken, $title, $body, $data = [])
    {
        $accessToken = self::getAccessToken();
        
        $client = new Client();
        try {
            $response = $client->post('https://fcm.googleapis.com/v1/projects/fir-demo-c5451/messages:send', [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $deviceToken,
                        'notification' => [
                            'title' => $title,
                            'body'  => $body,
                        ],
                        'data' => $data,
                    ],
                ],
            ]);
    
            return json_decode($response->getBody(), true);
        } catch (Exception $e) {}
       
    }
}