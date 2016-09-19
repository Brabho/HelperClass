<?php

/*
 * PDO Database Class
 */

class db {

    private $contodb;

    /*
     * Connect to Database
     */

    function __construct($db_id) {

        $host = $db_id['host'];
        $db_name = $db_id['db_name'];
        $user = $db_id['user'];
        $pass = $db_id['pass'];

        try {
            $this->contodb = new PDO("mysql:$host=;dbname=$db_name;charset=utf8", $user, $pass);
            $this->contodb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->contodb;
        } catch (PDOException $ex) {
            return false;
        }
    }

    /*
     * Query, Bind, Execute, Result
     */

    public function qber($query, $bind = array(), $arr = array()) {
        $dbh = $this->contodb->prepare($query);

        if (is_array($bind) && count($bind) > 0) {
            $arrKeys = array_keys($bind);
            for ($i = 0; $i < count($bind); $i++) {
                $dbh->bindValue($arrKeys[$i], $bind[$arrKeys[$i]]);
            }
        }

        $exeRes = $dbh->execute();

        if (isset($arr['result']) && $arr['result'] === true) {
            $result = array();
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
        unset($query, $bind, $arr, $result, $dbh, $arrKeys);
    }

    /*
     * Table Exists (Bool)
     */

    public function table_exists($tablename) {
        $tablename = preg_replace('/[^a-zA-Z0-9_]/', '', $tablename);
        try {
            $check = $this->contodb->query("SELECT 1 FROM $tablename LIMIT 1");
        } catch (Exception $e) {
            return false;
        }
        return $check !== false;
    }

    /*
     * Search & Replace
     */

    public function snr($arr = array()) {
        $this->contodb->query('UPDATE ' . $arr['table'] . ' SET ' . $arr['column'] . ' = 
		            REPLACE (' . $arr['column'] . ', "' . $arr['search'] . '", "' . $arr['replace'] . '")
		            WHERE `' . $arr['column'] . '` LIKE "%' . $arr['search'] . '%" ');
        unset($arr);
    }

    function __destruct() {
        unset($this);
    }

}

?>