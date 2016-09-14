<?php 

class units {
        
    public function byets($para) {
        $num = preg_replace('/[^0-9]/', '', $para);
        $key = preg_replace('/[^A-Z]/', '', $para);

        $arr = [
            'B' => 1,
            'K' => 1024,
            'M' => 1048576,
            'G' => 1073741824,
            'T' => 1099511627776
        ];
        
        if (array_key_exists($key, $arr)) {
            return $num * $arr[$key];
        }        
        unset($para, $num, $key, $arr);
    }
        
    public function times($para) {
        $num = preg_replace('/[^0-9]/', '', $para);
        $key = preg_replace('/[^a-z]/', '', $para);

        $arr = [
            'second'    => 1,
            'minute'    => 60,
            'hour'      => 3600,
            'day'       => 86400,
            'week'      => 604800,
            'month'     => 2592000,
            'year'      => 31536000,
            'decade'    => 315360000
        ];         
        
        if (array_key_exists($key, $arr)) {
            return $num * $arr[$key];
        }
        unset($para, $num, $key, $arr);
    }
    
    public function seconds ($para) {
        $num = preg_replace('/[^0-9]/', '', $para);
        $key = preg_replace('/[^a-z]/', '', $para);
        
        $arr = [
            'milli'     => 1000,
            'micro'     => 1000000,
            'nano'      => 1000000000
        ];
        
        if (array_key_exists($key, $arr)) {
            return $num * $arr[$key];
        }
        unset($para, $num, $key, $arr);
    }
        
    function __destruct() {
        unset($this);
    }
}


?>