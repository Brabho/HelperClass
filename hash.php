<?php

/*
 * Hashing Class
 */

class hash {

    private function hashing($str, $arr = array()) {

        if (!array_key_exists('algo1', $arr)) {
            $arr['algo1'] = 'sha256';
        }
        if (!array_key_exists('algo2', $arr)) {
            $arr['algo2'] = 'sha512';
        }

        $str = base64_encode(serialize(str_rot13($str)));
        $str = $arr['p'] . $str . $arr['s'];
        $hash = hash($arr['algo1'], $str);

        $salt = base64_encode(serialize(str_rot13($arr['p'] . $arr['s'])));
        $salt = hash($arr['algo2'], $salt);

        $hash_s = str_split($hash, 1);
        $salt_s = str_split($salt, 1);

        $hval = '';
        for ($i = 0; $i < 128; $i++) {
            if (isset($hash_s[$i])) {
                $hval .= $hash_s[$i];
            }
            if (isset($salt_s[$i])) {
                $hval .= $salt_s[$i];
            }
            if (!isset($hash_s[$i]) && !isset($salt_s[$i])) {
                break;
            }
        }

        unset($str, $arr, $hash, $salt, $hash_s, $salt_s, $i);
        return substr($hval, 2, -2);
    }

    /*
     * Value Hash Function
     */

    public function val($str, $arr = array()) {
        return $this->hashing($str, $arr);
        unset($str, $arr);
    }

    /*
     * Hashing password
     */

    public function pass($pass) {
        $options = [
            'cost' => 12,
            'salt' => mcrypt_create_iv(24, MCRYPT_DEV_URANDOM),
        ];
        $pass = password_hash($pass, PASSWORD_BCRYPT, $options);
        unset($options);
        return $pass;
        unset($pass);
    }

    function __destruct() {
        unset($this);
    }

}

?>