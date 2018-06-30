<?php

/*
 * FTP Class
 */

class ftp {

    private $conn;
    private $login;
    public $CONNECTION;

    function __construct() {
        $this->CONNECTION = [];
    }

    /*
     * Connect to Server
     */

    public function connect() {

        if (!isset($this->CONNECTION['SECURE'])) {
            $this->CONNECTION['SECURE'] = false;
        }

        if (!isset($this->CONNECTION['PORT'])) {
            $this->CONNECTION['PORT'] = '21';
        }

        if (!isset($this->CONNECTION['TIMEOUT'])) {
            $this->CONNECTION['TIMEOUT'] = '86400';
        }

        if ($this->CONNECTION['SECURE'] === true) {
            $this->conn = ftp_ssl_connect($this->CONNECTION['HOST'], $this->CONNECTION['PORT'], $this->CONNECTION['TIMEOUT']);
        } else {
            $this->conn = ftp_connect($this->CONNECTION['HOST'], $this->CONNECTION['PORT'], $this->CONNECTION['TIMEOUT']);
        }

        if ($this->conn) {
            echo 'yes';
            return $this->conn;
        } else {
            return false;
        }
    }

    /*
     * Login to Server
     */

    public function login() {
        $this->login = ftp_login($this->conn, $this->CONNECTION['USER'], $this->CONNECTION['PASS']);
        if ($this->login) {
            return $this->login;
        }
        return false;
    }

    /*
     * File Put
     */

    public function put($local, $server) {
        return (ftp_put($this->conn, $server, $local, FTP_ASCII)) ? true : false;
    }

    /*
     * File Get
     */

    public function get($local, $server) {
        return (ftp_get($this->conn, $local, $server, FTP_BINARY)) ? true : false;
    }

    /*
     * File Delete
     */

    public function del($path) {
        return (ftp_delete($this->conn, $path)) ? true : false;
    }

    /*
     * Files and Folder/Directory List
     */

    public function f_list($path = '/') {
        ftp_pasv($this->conn, true);
        return ftp_rawlist($this->conn, $path);
    }

    /*
     * Close Connection
     */

    public function close() {
        if (isset($this->conn)) {
            ftp_close($this->conn);
        }
    }

}
