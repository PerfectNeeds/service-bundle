<?php

namespace PN\ServiceBundle\Utils;

class IPService
{

    public static function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if (strpos($ip, ",") !== false) {
            return trim(explode(",", $ip)[0]);
        }

        return $ip;
    }

    public static function getIPLocation($ip = null)
    {
        if ($ip == null) {
            $ip = self::getIp();
        }

        return json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
    }

}

?>