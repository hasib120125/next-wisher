<?php

use App\Utils\CurlUtils;

function _curl($url)
{
    $curl = new CurlUtils($url);

    return $curl;
}