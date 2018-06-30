<?php

/*
 * SSH SFTP SCP
 */

class sftp {

    private $conn;
    private $status;
    public $CONNECTION;

    function __construct() {
        $this->CONNECTION = [];
        $this->status = [];
    }

    /*
     * Connect to Server
     */

    public function connect() {

        if (!isset($this->CONNECTION['PORT'])) {
            $this->CONNECTION['PORT'] = 22;
        }

        if (!isset($this->CONNECTION['PERMISSION'])) {
            $this->CONNECTION['PERMISSION'] = 0777;
        }

        $this->conn = ssh2_connect($this->CONNECTION['HOST'], $this->CONNECTION['PORT']);

        if ($this->conn) {

            if (ssh2_auth_password($this->conn, $this->CONNECTION['USER'], $this->CONNECTION['PASS'])) {
                $this->status[0] = 'success';
                $this->status['conn'] = $this->conn;
            } else {
                $this->status[0] = 'error';
                $this->status['reason'] = 'auth';
            }
        } else {
            $this->status[0] = 'error';
            $this->status['reason'] = 'connect';
        }

        return $this->status;
    }

    /*
     * File Put
     */

    public function put($local, $server) {
        if ($this->status[0] === 'success') {
            ssh2_scp_send($this->conn, $local, $server, $this->CONNECTION['PERMISSION']);
        }
    }

    /*
     * File Get
     */

    public function get($local, $server) {
        if ($this->status[0] === 'success') {
            ssh2_scp_recv($this->conn, $server, $local);
        }
    }

    /*
     * File Delete
     */

    public function del($path) {
        if ($this->status[0] === 'success') {
            $sftp = ssh2_sftp($this->conn);
            ssh2_sftp_unlink($sftp, $path);
        }
    }

    /*
     * File Exists
     */

    public function f_exists($path) {
        if ($this->status[0] === 'success') {
            $sftp = ssh2_sftp($this->conn);
            return file_exists('ssh2.sftp://' . $sftp . $path);
        }
    }

}
