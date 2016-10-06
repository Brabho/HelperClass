<?php

/*
 * Custom Functions
 */

class cf {
    /*
     * Isset Variable
     */

    public function is_var(&$var, $match = null) {
        return (isset($var) && strlen($var) > 0 && $var !== null && !empty($var));
        unset($var, $match);
    }

    /*
     * Isset Array Key
     */

    public function is_arr(&$var, $key, $match = null) {
        return (isset($var) && is_array($var) && array_key_exists($key, $var) && $var[$key] !== null && !empty($var[$key]));
        unset($var, $key, $match);
    }

    /*
     * Check Valid JSON
     */

    public function is_json($str) {
        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
        unset($str);
    }

    /*
     * Case (i)sensetive Value Match
     */

    public function val_match($var, $match, $case = 'i') {
        if ($case === 'i') {
            return (strtolower($var) === strtolower($match));
        } else {
            return ($var === $match);
        }
        unset($var, $match, $case);
    }

    /*
     * Work if there is no key or initizer key
     */

    public function shuffle_arr($arr = []) {
        $already = [];
        $count = count($arr);
        $new_arr = [];

        while (count($already) < $count || count($new_arr) < $count) {
            $rand = mt_rand(0, $count - 1);

            if (!in_array($rand, $already)) {
                $already[] = $rand;
                $new_arr[] = $arr[$rand];
            }
        }
        return $new_arr;
        unset($arr, $already, $count, $new_arr);
    }

    /*
     * Redirect
     */

    public function redirect($link, $refresh = '') {
        ob_start();
        if (ob_get_contents()) {
            ob_end_clean();
        }
        if (strlen($refresh) > 0 && is_numeric($refresh)) {
            header('Refresh: ' . $refresh . '; url=' . $link);
        } else {
            header('Location: ' . $link);
        }
        unset($link, $refresh);
        die('<h1>Unable to Redirect</h1>');
    }

    /*
     * Local Date
     */

    public function localDate($timezone = null, $time = null, $ptrn = 'd-m-Y h:i:sa') {
        if (isset($timezone) && function_exists('date_default_timezone_set')) {
            date_default_timezone_set($timezone);
        }
        if (!isset($time)) {
            $time = time();
        }
        return date($ptrn, $time);
        unset($timezone, $time, $ptrn);
    }

    /*
     * Download File
     */

    public function download($file, $param = []) {
        if (file_exists($file)) {

            if (ob_get_contents()) {
                ob_end_clean();
            }
            if (!array_key_exists('file_name', $param)) {
                $param['ame'] = ucfirst(strtolower(basename($file)));
            }
            if (!array_key_exists('file_type', $param)) {
                $param['type'] = 'application/octet-stream';
            }
            $param['length'] = sprintf("%u", filesize($file));

            header('Content-Description: File Transfer');
            header("Content-Type: application/force-download");
            header("Content-Type: application/download");
            header('Content-Type: ' . $param['file_type']);
            header('Content-Disposition: attachment; filename=' . $param['file_name']);
            header('Content-Length: ' . $param['file_length']);
            header('Content-Transfer-Encoding: binary');
            header('Connection: Keep-Alive');
            header('Expires: 0');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            ob_clean();
            flush();
            readfile($file);
            unset($file, $param);
        } else {
            return false;
        }
    }

    /*
     * PHP Comment Read
     */

    public function comment_read($file_name, $count = 'all') {
        $tokens = token_get_all(file_get_contents($file_name));
        $comments = [];
        foreach ($tokens as $token) {
            if ($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
                if (isset($token[1])) {
                    $comments[] = $token[1];
                } else {
                    $no_comment = true;
                }
            }
        }
        if (!isset($no_comment)) {
            if ($count === 'all') {
                return $comments;
            } else {
                return $comments[$count];
            }
        } else {
            return false;
        }
        unset($file_name, $count, $tokens, $comments, $token, $no_comment);
    }

    /*
     * Mod `file_put_contents` 
     */

    public function file_make_contents($file_path, $content = '') {
        $file = basename($file_path);
        $path = str_replace(basename($file_path), '', $file_path);

        if ($file_path !== basename($file_path) && !is_dir($path)) {
            mkdir($path, 0777, TRUE);
        }
        file_put_contents($file_path, $content);
        unset($file_path, $content, $path);
    }

    /*
     * Variable Clean
     */

    public function varClean() {
        foreach (array_keys(get_defined_vars()) as $var) {
            if ($var === 'GLOBALS' || $var === '_POST' || $var === '_GET' || $var === '_COOKIE' ||
                    $var === '_FILES' || $var === '_REQUEST' || $var === '_SERVER' || $var === '_ENV') {

                continue;
            }
            unset($var);
        }
        clearstatcache();
    }

    /*
     * Convert Number to Word
     */

    public function cntw($number) {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );
        if (!is_numeric($number)) {
            return FALSE;
        }
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {

            trigger_error(
                    'cntw only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
            );
            return FALSE;
        }
        if ($number < 0) {
            return $negative . $this->cntw(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (TRUE) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->cntw($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->cntw($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->cntw($remainder);
                }
                break;
        }
        if (NULL !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        unset($number, $hyphen, $conjunction, $separator, $negative, $decimal, $dictionary, $words);
        return $string;
        unset($string);
    }

    /*
     * Convert Word to Number
     */

    public function cwtn($data) {
        $data = strtr(
                $data, array(
            'zero' => '0',
            'a' => '1',
            'one' => '1',
            'two' => '2',
            'three' => '3',
            'four' => '4',
            'five' => '5',
            'six' => '6',
            'seven' => '7',
            'eight' => '8',
            'nine' => '9',
            'ten' => '10',
            'eleven' => '11',
            'twelve' => '12',
            'thirteen' => '13',
            'fourteen' => '14',
            'fifteen' => '15',
            'sixteen' => '16',
            'seventeen' => '17',
            'eighteen' => '18',
            'nineteen' => '19',
            'twenty' => '20',
            'thirty' => '30',
            'forty' => '40',
            'fourty' => '40',
            'fifty' => '50',
            'sixty' => '60',
            'seventy' => '70',
            'eighty' => '80',
            'ninety' => '90',
            'hundred' => '100',
            'thousand' => '1000',
            'million' => '1000000',
            'billion' => '1000000000',
            'and' => '',
                )
        );
        $parts = array_map(
                function ($val) {
            return floatval($val);
        }, preg_split('/[\s-]+/', $data)
        );
        $stack = new SplStack;
        $sum = 0;
        $last = NULL;
        foreach ($parts as $part) {
            if (!$stack->isEmpty()) {
                if ($stack->top() > $part) {
                    if ($last >= 1000) {
                        $sum += $stack->pop();
                        $stack->push($part);
                    } else {
                        $stack->push($stack->pop() + $part);
                    }
                } else {
                    $stack->push($stack->pop() * $part);
                }
            } else {
                $stack->push($part);
            }
            $last = $part;
        }
        unset($data, $parts, $last, $parts);
        return $sum + $stack->pop();
        unset($sum, $stack);
    }

    function __destruct() {
        unset($this);
    }

}

?>