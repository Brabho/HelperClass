<?php

class header_status {
    /*
     * 200 Status
     * Ok Page
     */

    public function ok() {
        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
        header('Status: 200 OK');
    }

    /*
     * 400 Error
     * Bad Request
     */

    public function bad_request() {
        header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
        header('Status: 403 Bad Request');
    }

    /*
     * 401 Error
     * Unauthorized
     */

    public function unauthorized() {
        header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
        header('Status: 401 Unauthorized');
    }

    /*
     * 403 Error
     * Forbidden
     */

    public function forbidden() {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        header('Status: 403 Forbidden');
    }

    /*
     * 404 Error
     * Page not found
     */

    public function not_found() {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        header('Status: 404 Not Found');
    }

    /*
     * 503 Error
     * Temporarily Unavailable
     */

    public function unavailable() {
        header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
    }

    function __destruct() {
        unset($this);
    }

}

?>