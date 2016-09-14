<?php

/*
 * Cookie Class
 */

class cookie {

    public function set($name, $value, $extime = 86400, $path = '/') {
        setcookie($name, $value, time()+$extime, $path);
        unset($name, $value, $extime, $path);
    }

    public function get($name) {
        return (array_key_exists($name, $_COOKIE) && $_COOKIE[$name] !== null && !empty($_COOKIE[$name])) ? $_COOKIE[$name] : false;
    }

    public function remove($name, $path = '/') {
        setcookie($name, '', time()-86400, $path);
        unset($name);
    }

    public function removeAll($path = '/') {
        foreach ($_COOKIE as $cookie => $c_val) {
            setcookie($cookie, '', time()-86400, $path);
        }
    }

    function __destruct() {
        unset($this);
    }

}

?>