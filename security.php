<?php

/*
 * Security Class
 */

class security {
    /*
     * Request Per Second
     */

    public function req_sec($exp = '3') {

        if ((array_key_exists('HTTPS', $_SERVER) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === 1)) ||
                (array_key_exists('HTTP_X_FORWARDED_PROTO', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {

            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        $uri = hash('sha384', $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $comp = $uri . '|' . time();

        if (!isset($_SESSION['req_sec'])) {
            $_SESSION['req_sec'] = $comp;
        }

        list($_uri, $_exp) = explode('|', $_SESSION['req_sec']);
        if ($_uri === $uri && (time() - $_exp < $exp)) {
            return false;
        } else {
            return true;
        }

        unset($protocol, $uri, $comp, $_uri, $_exp);
    }

    /*
     * CSRF Form
     */

    public function csrf_form() {
        /*
         * Creating Token
         */
        $crypto_strong = true;
        $token = bin2hex(openssl_random_pseudo_bytes(128, $crypto_strong));
        $_SESSION['csrf_token'] = $token;

        /*
         * Echo Form Field
         */
        echo PHP_EOL . '<input type="hidden" name="csrf_token" value="' . $token . '" />' . PHP_EOL;

        unset($token);
    }

    /*
     * CSRF Validation
     */

    public function csrf_valid() {
        /*
         * Remove Session CSRF Token
         */

        if (isset($_SESSION['csrf_token'])) {
            $csrf_token = $_SESSION['csrf_token'];
            $_SESSION['csrf_token'] = null;
            unset($_SESSION['csrf_token']);
        } else {
            return false;
        }

        if (isset($_POST['csrf_token'])) {
            if ($csrf_token === $_POST['csrf_token']) {
                return true;
            }
            return false;
        }
        return false;
    }

    function __destruct() {
        unset($this);
    }

}

?>