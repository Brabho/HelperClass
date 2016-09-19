<?php

/*
 * Session Class
 */

class session {
    /*
     * Start Session
     */

    public function start() {
        if ((session_id() == '') ||
                (session_status() == PHP_SESSION_NONE) ||
                (session_status() !== PHP_SESSION_ACTIVE)) {

            if (!headers_sent()) {
                session_start();
                session_regenerate_id(true);
            } else {
                return false;
            }
        }
    }

    /*
     * Set Session
     */

    public function set($key, $value) {
        $this->start();
        $_SESSION[$key] = $value;
        unset($key, $value);
    }

    /*
     * Get Session
     */

    public function get($name) {
        $this->start();
        return (array_key_exists($name, $_SESSION) && $_SESSION[$name] !== null && !empty($_SESSION[$name])) ? $_SESSION[$name] : false;
    }

    /*
     * Remove Session
     */

    public function remove($key) {
        $this->start();
        $_SESSION[$key] = NULL;
        unset($_SESSION[$key], $key);
    }

    /*
     * Remove All Session
     */

    public function removeAll() {
        $this->start();
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 86400, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_unset();
    }

    /*
     * Remove All Session
     * Session Destory 
     */

    public function destroy() {
        $this->start();
        session_regenerate_id(true);
        $this->removeAll();
        session_destroy();
    }

    function __destruct() {
        unset($this);
    }

}

?>