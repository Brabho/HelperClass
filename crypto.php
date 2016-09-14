<?php

/*
 * Encrypt & Decrypt String
 */

class crypto {

    private function encrypt($val, $arr = array()) {

        $key = hash('sha256', $arr['p'] . $arr['s']);
        $val = $arr['p'] . $val . $arr['p'];
        $val = serialize(str_rot13($val));

        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', $key);
        $mac = hash_hmac('sha256', $val, substr(bin2hex($key), -32));
        $crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $val . $mac, MCRYPT_MODE_CBC, $iv);
        $crypt = base64_encode($crypt) . '|' . base64_encode($iv);
        unset($val, $arr, $key, $iv, $mac);
        return $crypt;        
    }

    private function decrypt($val, $arr = array()) {

        $key = hash('sha256', $arr['p'] . $arr['s']);
        $val = explode('|', $val . '|');
        $deco = base64_decode($val[0]);
        $iv = base64_decode($val[1]);

        if(strlen($iv) === mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)) {
            $key = pack('H*', $key);
            $decry = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $deco, MCRYPT_MODE_CBC, $iv));
            $mac = substr($decry, -64);
            $decry = substr($decry, 0, -64);
            $cmac = hash_hmac('sha256', $decry, substr(bin2hex($key), -32));

            if($mac === $cmac) {
                $decry = str_rot13(unserialize($decry));
                $decry = str_replace([$arr['p'], $arr['s']], '', $decry);                
                unset($val, $arr, $key, $iv, $mac, $deco, $cmac);
                return $decry;
            }
        }        
    }

    public function val($str, $arr = array()) {
        switch($arr['do']) {
            case 'en':
                return $this->encrypt($str, $arr);
                break;

            case 'de':
                return $this->decrypt($str, $arr);
                break;
        }
    }

    function __destruct() {
        unset($this);
    }

}

?>