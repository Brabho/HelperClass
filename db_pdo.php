<?php

/*
 * Mysql Database
 * PDO Class
 */

class db_pdo {

    private $contodb;

    /*
     * Connect to Database
     */

    public function connect($db_id) {

        $host = $db_id['HOST'];
        $db_name = $db_id['DB_NAME'];
        $user = $db_id['USER'];
        $pass = $db_id['PASS'];

        if (isset($db_id['charset'])) {
            $charset = $db_id['charset'];
        } else {
            $charset = 'utf8';
        }

        try {
            $this->contodb = new PDO("mysql:host=$host;dbname=$db_name;charset=$charset", $user, $pass);
            $this->contodb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            unset($db_id, $host, $db_name, $user, $pass, $charset);
            return $this->contodb;
        } catch (PDOException $ex) {
            unset($db_id, $host, $db_name, $user, $pass, $charset);
            return false;
        }
    }

    /*
     * Ping & Re Connect
     */

    public function ping($db_id, $re = null) {
        if (isset($this->contodb)) {
            try {
                $this->contodb->query('SELECT 1');
                return true;
            } catch (Exception $ex) {
                if (isset($re) && $re === true) {
                    return $this->connect($db_id);
                }
                return false;
            }
        } else {
            if (isset($re) && $re === true) {
                return $this->connect($db_id);
            }
            return false;
        }
    }

    /*
     * Query, Bind, Execute, Result
     */

    public function qber($query, $bind = [], $arr = []) {
        if ($this->ping(null)) {
            $dbh = $this->contodb->prepare($query);

            if (isset($bind) && is_array($bind) && count($bind) > 0) {
                $arrKeys = array_keys($bind);
                for ($i = 0; $i < count($bind); $i++) {
                    $dbh->bindValue($arrKeys[$i], $bind[$arrKeys[$i]]);
                }
            }

            $exeRes = $dbh->execute();

            if (isset($arr['result']) && $arr['result'] === true) {
                $result = [];
                if ($exeRes) {
                    $result[0] = 'success';
                } else {
                    $result[0] = 'fail';
                }
                $result['rows'] = $dbh->rowCount();
            }

            if (isset($result) && isset($arr['fetch'])) {
                if (!isset($arr['fetch_arg'])) {
                    $arr['fetch_arg'] = null;
                }
                $result['fetch'] = $dbh->{$arr['fetch']}($arr['fetch_arg']);
            }

            return (isset($result)) ? $result : $dbh;
        }
        unset($query, $bind, $arr, $result, $dbh, $arrKeys);
    }

    /*
     * Table Exists (Bool)
     */

    public function table_exists($tablename) {
        if ($this->ping(null)) {
            $tablename = preg_replace('/[^a-zA-Z0-9_]/', '', $tablename);
            try {
                $check = $this->contodb->query("SELECT 1 FROM $tablename LIMIT 1");
            } catch (Exception $e) {
                return false;
            }
            return $check !== false;
        }
    }

    /*
     * Search & Replace
     */

    public function snr($table, $column, $search, $replace) {
        if ($this->ping(null)) {
            $this->contodb->query("UPDATE $table 
                SET $column = 
                REPLACE ($column, '$search', '$replace')");
        }
        unset($table, $column, $search, $replace);
    }

    /*
     * Database Pagination
     */

    public function pagination($query, $bind = [], $page_no = 1, $row = 10) {

        $result = [];

        if ($this->ping(null)) {
            $getting = $this->qber($query, $bind, ['result' => true]);

            $last = ceil($getting['rows'] / $row);

            if ($last < 1) {
                $last = 1;
            }

            if ($page_no > $last) {
                $page_no = $last;
            }

            $limit = ' LIMIT ' . ($page_no - 1) * $row . ',' . $row;

            $result = $this->qber($query . $limit, $bind, ['result' => true, 'fetch' => 'fetchAll']);
            $result['total_rows'] = $getting['rows'];
            $result['total_pages'] = $last;

            unset($query, $bind, $page_no, $row, $getting, $last, $limit);
            return $result;
        }
    }

    function __destruct() {
        unset($this);
    }

}

?>