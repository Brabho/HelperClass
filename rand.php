<?php

/*
 * Random class
 * Generate Number, String, Crypto String
 */

class rand {
    /*
     * Random Number
     * Default 40
     * Maximum 60
     */

    public function num($length = 40) {
        for ($i = -1; $i <= 4; $i++) {
            $bytes = openssl_random_pseudo_bytes(8, $crypto_strong);
            $num = hexdec(bin2hex($bytes));
        }
        $mtim = explode('.', microtime(true));
        $num = rand(1000000000, 9999999999) . $num . $mtim[0] . mt_rand(1000000000, 9999999999) . $mtim[1] . time();
        $num = preg_replace('/[^0-9]/', '', serialize($num));
        $num = str_shuffle($num);
        return substr($num, 0);
        unset($length, $mtim, $num);
    }

    /*
     * Random String
     * Default 40
     * Maximum 60
     */

    public function str($length = 40) {
        $str = uniqid(microtime(true), true);
        $str = rand(10000, 99999) . $str . mt_rand(100000, 999999) . time();
        $str = base64_encode(serialize($str));
        $str = hash('sha256', $str);
        return substr($str, 0, $length);
        unset($length, $str);
    }

    /*
     * Random Crypto String
     */

    public function crypt($bit = 128) {
        for ($i = -1; $i <= 4; $i++) {
            $bytes = openssl_random_pseudo_bytes($bit, $crypto_strong);
            $crypt = bin2hex($bytes);
        }
        return $crypt;
    }

    function __destruct() {
        unset($this);
    }

}

?>