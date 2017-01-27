<?php

/*
 * Cookie Class
 */

class cookie {
    /*
     * Set Cookie
     */

    public function set($name, $value, $extime = 86400, $arr = []) {

        if (!array_key_exists('path', $arr)) {
            $arr['path'] = '/';
        }
        if (!array_key_exists('domain', $arr)) {
            $arr['domain'] = null;
        }
        if (!array_key_exists('secure', $arr)) {
            $arr['secure'] = null;
        }
        if (!array_key_exists('http', $arr)) {
            $arr['http'] = true;
        }

        setcookie($name, $value, time() + $extime, $arr['path'], $arr['domain'], $arr['secure'], $arr['http']);
        unset($name, $value, $extime, $arr);
    }

    /*
     * Get Cookie
     */

    public function get($name) {
        return (array_key_exists($name, $_COOKIE) && $_COOKIE[$name] !== null && !empty($_COOKIE[$name])) ? $_COOKIE[$name] : false;
    }

    /*
     * Remove Single Cookie
     */

    public function remove($name, $path = '/') {
        setcookie($name, '0', time() - 86400, $path);
        unset($name);
    }

    /*
     * Remove All Cookies
     */

    public function removeAll($path = '/') {
        foreach ($_COOKIE as $cookie => $c_val) {
            setcookie($cookie, '0', time() - 86400, $path);
        }
    }

    function __destruct() {
        unset($this);
    }

}

?>