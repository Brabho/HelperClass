<?php

/*
 * PDO Database Class
 */

class db {

    private $contodb;

    /*
     * Connect to Database
     */

    public function connect($db_id) {

        $host = $db_id['HOST'];
        $db_name = $db_id['DB_NAME'];
        $user = $db_id['USER'];
        $pass = $db_id['PASS'];

        try {
            $this->contodb = new PDO("mysql:$host=;dbname=$db_name;charset=utf8", $user, $pass);
            $this->contodb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->contodb;
        } catch (PDOException $ex) {
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
                $result['fetch'] = $dbh->$arr['fetch']($arr['fetch_arg']);
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

    public function snr($arr = []) {
        if ($this->ping(null)) {
            $this->contodb->query('UPDATE ' . $arr['table'] . ' SET ' . $arr['column'] . ' = 
		            REPLACE (' . $arr['column'] . ', "' . $arr['search'] . '", "' . $arr['replace'] . '")
		            WHERE `' . $arr['column'] . '` LIKE "%' . $arr['search'] . '%" ');
        }
        unset($arr);
    }

    function __destruct() {
        unset($this);
    }

}

?>