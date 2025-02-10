<?php

namespace App\Utils;

class CurlUtils
{
    public $ch;

    public function __construct(public $url)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    }

    public function get($data)
    {
        $result = curl_exec($this->ch);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
        if (curl_errno($this->ch)) {
            return curl_error($this->ch);
        }

        return $result;
    }

    public function post($data = [], $headers = [])
    {
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);


        $result = curl_exec($this->ch);
        if (curl_errno($this->ch)) {
            return curl_error($this->ch);
        }

        return $result;


        
        
    }
}
