<?php

/*
 * String Class
 */

class str {
    /*
     * HTML Encode
     */

    public function htm_enc($content, $arr = array()) {
        if (!array_key_exists('strip', $arr)) {
            $arr['strip'] = false;
        }
        if (!array_key_exists('allow', $arr)) {
            $arr['allow'] = '';
        }
        if (!array_key_exists('encode', $arr)) {
            $arr['encode'] = 'specialchars';
        }

        if ($arr['strip'] === true) {
            $content = strip_tags($content, $arr['allow']);
        }

        switch ($arr['encode']) {
            case 'specialchars':
                $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
                break;

            case 'entities':
                $content = htmlentities($content, ENT_QUOTES, 'UTF-8');
                break;
        }
        unset($arr);
        return $content;
    }

    /*
     * Rename File name
     */

    public function re_name($path, $nam) {
        $exp = explode('.', basename($path));
        return ($path === basename($path)) ? $nam . '.' . $exp[1] : dirname($path) . '/' . $nam . '.' . end($exp);
        unset($path, $nam, $exp);
    }

    /*
     * Mod Trim
     */

    public function trims($content, $delmi = " \t\n\r\0\x0B", $white = false) {
        $content = trim($content, $delmi);
        $content = ltrim($content, $delmi);
        $content = rtrim($content, $delmi);

        if ($white === true) {
            $content = preg_replace('/\s+/', $space, $content);
        }
        unset($delmi, $white);
        return $content;
    }

    public function strip_tags($text, $tags = '', $invert = false) {
        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique($tags[1]);
        if (is_array($tags) && count($tags) > 0) {
            if ($invert == false) {
                return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            } else {
                return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
            }
        } elseif ($invert == false) {
            return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }
        return $text;
    }

    /*
     * String to Hex
     */

    public function str2hex($string) {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0' . $hexCode, -2);
        }
        return strToUpper($hex);
        unset($string, $hex, $i, $ord, $hexCode);
    }

    /*
     * Hex to String
     */

    public function hex2str($hex) {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i+=2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
        unset($hex, $string, $i);
    }

    /*
     * Mod ucwords
     */

    public function ucWords($str) {
        return ucwords(strtolower($str));
        unset($str);
    }

    /*
     * Mod ucfirst
     */

    public function ucFirst($str) {
        return ucfirst(strtolower($str));
        unset($str);
    }

    function __destruct() {
        unset($this);
    }

}

?>