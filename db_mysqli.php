<?php

/*
 * MySQLi Database Class
 */

class db_mysqli {

    private $contodb;

    /*
     * Connect to Database
     */

    public function connect($db_id) {

        $host = $db_id['HOST'];
        $db_name = $db_id['DB_NAME'];
        $user = $db_id['USER'];
        $pass = $db_id['PASS'];

        $this->contodb = new mysqli("$host", "$user", "$pass", "$db_name");
        if ($this->contodb->connect_errno) {
            return false;
        }

        if (isset($db_id['charset'])) {
            $charset = $db_id['charset'];
        } else {
            $charset = 'utf8';
        }

        $this->contodb->set_charset($charset);

        unset($db_id, $host, $db_name, $user, $pass, $charset);
        return $this->contodb;
    }

    /*
     * Table Exists (Bool)
     */

    public function table_exists($tablename) {
        if ($this->contodb->ping()) {
            $tablename = preg_replace('/[^a-zA-Z0-9_]/', '', $tablename);
            $result = $this->contodb->query("SELECT 1 FROM $tablename LIMIT 1");
            return (isset($result->num_rows) && $result->num_rows > 0) ? true : false;
        }
    }

    /*
     * Search & Replace
     */

    public function snr($table, $column, $search, $replace) {
        if ($this->contodb->ping()) {
            $this->contodb->query("UPDATE $table 
                SET $column = 
                REPLACE ($column, '$search', '$replace')");
        }
        unset($table, $column, $search, $replace);
    }

}
