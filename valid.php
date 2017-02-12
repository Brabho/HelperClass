<?php

/*
 * Validation CLass
 */

class valid {
    /*
     * Alphabet
     */

    public function alpha($alpha, $let = 'all') {
        switch ($let) {
            case 'all':
                return (preg_match_all('/^[a-zA-Z]+$/i', $alpha)) ? TRUE : FALSE;
                break;

            case 'low':
                return (preg_match_all('/^[a-z]+$/', $alpha)) ? TRUE : FALSE;
                break;

            case 'up':
                return (preg_match_all('/^[A-Z]+$/', $alpha)) ? TRUE : FALSE;
                break;
        }
        unset($alpha, $let);
    }

    /*
     * Number
     */

    public function num($num) {
        return (preg_match_all('/^[0-9]+$/', $num) && !filter_var($num, FILTER_VALIDATE_INT) === false);
        unset($num);
    }

    /*
     * Email
     */

    public function email($email, $host = false) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) &&
                preg_match('/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i', $email)) {

            if ($host === true) {
                return (checkdnsrr(array_pop(explode("@", $email)), "MX")) ? true : false;
            }
            return true;
        }
        return false;
        unset($email, $host);
    }

    /*
     * IP
     */

    public function ip($ip) {
        return (filter_var($ip, FILTER_VALIDATE_IP));
        unset($ip);
    }

    /*
     * URL
     */

    public function url($str, $qstr = false) {
        $url = urldecode($str);
        if ($qstr === true) {
            return (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED));
        } else {
            return (filter_var($url, FILTER_VALIDATE_URL));
        }
        unset($str, $qstr, $url);
    }

    function __destruct() {
        unset($this);
    }

}

?>