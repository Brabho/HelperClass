<?php

/*
 * Helper Class 
 * Custom & Mod Functions
 */

/*
 * Isset Variable
 */

function is_var(&$var) {
    return (isset($var) && $var !== null && $var !== '' && !empty($var) && strlen(trim($var)) > 0);
}

/*
 * Isset Array Key
 */

function is_arr(&$var, $key) {
    return (isset($var) && is_array($var) && array_key_exists($key, $var) && $var[$key] !== null && $var[$key] !== '' && !empty($var[$key]));
}

/*
 * Check Valid JSON
 */

function is_json($str) {
    json_decode($str);
    return (json_last_error() === JSON_ERROR_NONE);
}

/*
 * Redirect
 */

function redirect($link, $refresh = '') {
    ob_start();
    while (ob_get_contents()) {
        ob_end_clean();
    }
    if (strlen($refresh) > 0 && is_numeric($refresh)) {
        header('Refresh: ' . $refresh . '; url=' . $link);
    } else {
        header('Location: ' . $link);
    }
    die('<h1>Unable to Redirect</h1>');
}

/*
 * Local Date
 */

function local_date($timezone = null, $time = null, $ptrn = 'd-m-Y h:i:sa') {
    if (isset($timezone) && function_exists('date_default_timezone_set')) {
        date_default_timezone_set($timezone);
    }
    if (!isset($time)) {
        $time = time();
    }
    return date($ptrn, $time);
}

/*
 * Time Ago Function
 */

function time_ago($time) {
    $periods = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade');
    $lengths = array('60', '60', '24', '7', '4.35', '12', '10');
    $now = time();
    $difference = $now - $time;
    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }
    $difference = round($difference);
    if ($difference != 1) {
        $periods[$j] .= "s";
    }
    return $difference . ' ' . $periods[$j];
}

/*
 * Download File
 */

function download($file, $param = []) {
    if (file_exists($file)) {
        while (ob_get_contents()) {
            ob_end_clean();
        }
        if (!array_key_exists('file_name', $param)) {
            $param['ame'] = ucwords(strtolower(basename($file)));
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
    } else {
        return false;
    }
}

/*
 * Make Directory
 */

function mk_dir($path) {
    $path = $path . '/';
    if (!is_dir($path)) {
        $directory_path = '';
        $directories = explode('/', $path);
        array_pop($directories);
        foreach ($directories as $directory) {
            $directory_path .= $directory . '/';
            if (!is_dir($directory_path)) {
                mkdir($directory_path);
                chmod($directory_path, 0777);
            }
        }
    }
}

/*
 * Copy Directory 
 */

function cp_dir($src, $dest) {
    if (!file_exists($dest)) {
        mkdir($dest);
    }
    foreach (scandir($src) as $file) {
        $srcfile = trim($src, '/') . '/' . $file;
        $destfile = trim($dest, '/') . '/' . $file;
        if (!is_readable($srcfile)) {
            continue;
        } if ($file != '.' && $file != '..') {
            if (is_dir($srcfile)) {
                if (!file_exists($destfile)) {
                    mkdir($destfile);
                } $this->copy($srcfile, $destfile);
            } else {
                copy($srcfile, $destfile);
            }
        }
    }
}

/*
 * Delete Directory 
 */

function del_dir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != '.' && $object != '..') {
                if (filetype($dir . '/' . $object) == 'dir') {
                    $this->del($dir . '/' . $object);
                } else {
                    unlink($dir . '/' . $object);
                }
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

/*
 * Directory Size 
 */

function dir_size($dir) {
    if (is_dir($dir)) {
        $size = 0;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $file) {
            if ($file->getFileName() != '..' && $file->getFileName() != '.') {
                $size += $file->getSize();
            }
        }
        return $size;
    }
    return false;
}

/*
 * Get Mime Type
 */

function mime_type($file) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    return finfo_file($finfo, $file);
    finfo_close($finfo);
}

/*
 * Uri Information
 */

function uri_info($link) {
    $allarr = parse_url($link);
    $domain = (!array_key_exists('host', $allarr)) ? $allarr['path'] : $allarr['host'];

    $allarr['query'] = explode('&', $allarr['query']);
    $allarr['ip'] = gethostbyname($domain);
    return $allarr;
}

/*
 * Slug / Link
 */

function slug($text, $charset = 'utf-8') {
    $text = htmlentities($text, ENT_NOQUOTES, $charset);
    $text = preg_replace('~&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);~', '\1', $text);
    $text = preg_replace('~&([A-za-z]{2})(?:lig);~', '\1', $text);
    $text = preg_replace('~&[^;]+;~', '', $text);
    return preg_replace('~[\s!*\'();:@&=+$,/?%#[\]]+~', '-', $text);
}

/*
 * Get Email, Image, Link By Regex
 */

function by_ptn($subject, $count = 'all', $pattern = null, $by = 'email') {
    if (!isset($pattern)) {
        switch ($by) {
            case 'email':
                $pattern = '/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/i';
                break;

            case 'img':
                $pattern = '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i';
                break;

            case 'link':
                $pattern = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/i';
                break;
        }
    }

    preg_match_all($pattern, $subject, $matches);
    if (array_key_exists(0, $matches)) {
        if ($count === 'all') {
            return $matches[0];
        } else {
            if (array_key_exists($count, $matches[0])) {
                return $matches[0][$count];
            }
            return false;
        }
    }
    return false;
}

/*
 * Encrypt Value
 */

function encrypt($val, $arr = array()) {
    $key = hash('sha256', $arr['p'] . $arr['s']);
    $val = $arr['p'] . $val . $arr['p'];
    $val = serialize(str_rot13($val));

    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
    $key = pack('H*', $key);
    $mac = hash_hmac('sha256', $val, substr(bin2hex($key), -32));
    $crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $val . $mac, MCRYPT_MODE_CBC, $iv);
    $crypt = base64_encode($crypt) . '|' . base64_encode($iv);
    return $crypt;
}

/*
 * Decrypt Value
 */

function decrypt($val, $arr = array()) {

    $key = hash('sha256', $arr['p'] . $arr['s']);
    $val = explode('|', $val . '|');
    $deco = base64_decode($val[0]);
    $iv = base64_decode($val[1]);

    if (strlen($iv) === mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)) {
        $key = pack('H*', $key);
        $decry = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $deco, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decry, -64);
        $decry = substr($decry, 0, -64);
        $cmac = hash_hmac('sha256', $decry, substr(bin2hex($key), -32));

        if ($mac === $cmac) {
            $decry = str_rot13(unserialize($decry));
            $decry = str_replace([$arr['p'], $arr['s']], '', $decry);
            return $decry;
        }
    }
}

/*
 * Random Number
 * Default 40
 * Maximum 60
 */

function rand_num($length = 40) {
    for ($i = -1; $i <= 4; $i++) {
        $bytes = openssl_random_pseudo_bytes(8, $crypto_strong);
        $num = hexdec(bin2hex($bytes));
    }
    $mtim = explode('.', microtime(true));
    $num = rand(1000000000, 9999999999) . $num . $mtim[0] . mt_rand(1000000000, 9999999999) . $mtim[1] . time();
    $num = preg_replace('/[^0-9]/', '', serialize($num));
    $num = str_shuffle($num);
    return substr($num, 0);
}

/*
 * Random String
 * Default 40
 * Maximum 60
 */

function rand_str($length = 40) {
    $str = uniqid(microtime(true), true);
    $str = rand(10000, 99999) . $str . mt_rand(100000, 999999) . time();
    $str = base64_encode(serialize($str));
    $str = hash('sha256', $str);
    return substr($str, 0, $length);
}

/*
 * Random Crypto String
 */

function rand_crypt($bit = 128) {
    $bit = $bit / 2;
    for ($i = -1; $i <= 4; $i++) {
        $bytes = openssl_random_pseudo_bytes($bit, $crypto_strong);
        $crypt = bin2hex($bytes);
    }
    return $crypt;
}

/*
 * Request Per Second
 */

function req_sec($exp = '3') {

    if ((array_key_exists('HTTPS', $_SERVER) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === 1)) ||
            (array_key_exists('HTTP_X_FORWARDED_PROTO', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {

        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }

    $uri = hash('sha384', $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    $comp = $uri . '|' . time();

    if (!isset($_SESSION['req_sec'])) {
        $_SESSION['req_sec'] = $comp;
    }

    list($_uri, $_exp) = explode('|', $_SESSION['req_sec']);

    return ($_uri === $uri && (time() - $_exp < $exp));
}

/*
 * CSRF Form
 */

function csrf_form() {
    $token = rand_crypt();
    $_SESSION['csrf_token'] = $token;

    /*
     * Echo Form Field
     */
    echo PHP_EOL . '<input type="hidden" name="csrf_token" value="' . $token . '" />' . PHP_EOL;
}

/*
 * CSRF Validation
 */

function csrf_valid() {
    /*
     * Remove Session CSRF Token
     */

    if (isset($_SESSION['csrf_token'])) {
        $csrf_token = $_SESSION['csrf_token'];
        $_SESSION['csrf_token'] = null;
        unset($_SESSION['csrf_token']);
    } else {
        return false;
    }

    if (isset($_POST['csrf_token'])) {
        if ($csrf_token === $_POST['csrf_token']) {
            return true;
        }
        return false;
    }
    return false;
}

/*
 * HTML Encode
 */

function htm_en($content, $arr = []) {
    if (!array_key_exists('strip', $arr)) {
        $arr['strip'] = false;
    }
    if (!array_key_exists('allow', $arr)) {
        $arr['allow'] = '';
    }
    if (!array_key_exists('encode', $arr)) {
        $arr['encode'] = 'specialchars';
    }
    if (!array_key_exists('charset', $arr)) {
        $arr['charset'] = 'utf-8';
    }

    if ($arr['strip'] === true) {
        $content = strip_tags($content, $arr['allow']);
    }

    switch ($arr['encode']) {
        case 'specialchars':
            $content = htmlspecialchars($content, ENT_QUOTES, $arr['charset']);
            break;

        case 'entities':
            $content = htmlentities($content, ENT_QUOTES, $arr['charset']);
            break;
    }
    return $content;
}

/*
 * Rename File name
 */

function re_name($path, $nam) {
    $exp = explode('.', basename($path));
    return ($path === basename($path)) ? $nam . '.' . $exp[1] : dirname($path) . '/' . $nam . '.' . end($exp);
}

/*
 * Mod Trim
 */

function trims($content, $delmi = " \t\n\r\0\x0B", $white = null) {
    $content = trim($content, $delmi);
    $content = ltrim($content, $delmi);
    $content = rtrim($content, $delmi);

    if (isset($white)) {
        $content = preg_replace('/\s+/', $white, $content);
    }
    return $content;
}

/*
 * Mod Strip Tags
 * Remove Tags with contents
 */

function m_strip_tags($text, $tags = '', $invert = false) {
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
 * Mod ucwords
 */

function uc_words($str) {
    return ucwords(strtolower($str));
}

/*
 * Mod ucfirst
 */

function uc_first($str) {
    return ucfirst(strtolower($str));
}

/*
 * Add to Zip
 */

function zip_add($source, $destiny) {
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }
    $source = str_replace('\\', '/', $source);
    $zip = new ZipArchive();
    if (!$zip->open($destiny, ZIPARCHIVE::CREATE)) {
        return false;
    }

    if (is_dir($source)) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($files as $file) {

            $file = str_replace('\\', '/', $file);
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir($file)) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } elseif (is_file($file)) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    } elseif (is_file($source)) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }
    return $zip->close();
}

/*
 * Extract Zip
 */

function zip_extract($source, $destiny) {
    $zip = new ZipArchive;
    if ($zip->open($source)) {
        $zip->extractTo($destiny);
        $zip->close();
        return true;
    }
    return false;
}

/*
 * Valid Alphabet
 */

function valid_alpha($alpha, $let = 'all') {
    switch ($let) {
        case 'all':
            return (preg_match_all('/^[a-zA-Z]+$/i', $alpha)) ? TRUE : FALSE;
            break;

        case 'low':
            return (preg_match_all('/^[a-z]+$/', $alpha)) ? TRUE : FALSE;
            break;

        case 'up':
            return (preg_match_all('/^[A-Z]+$/', $alpha)) ? TRUE : FALSE;
            break;
    }
}

/*
 * Valid Number
 */

function valid_num($num) {
    return (preg_match_all('/^[0-9]+$/', $num) && !filter_var($num, FILTER_VALIDATE_INT) === false);
}

/*
 * Valid Email
 */

function valid_email($email, $host = false) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL) &&
            preg_match('/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i', $email)) {

        if ($host === true) {
            return (checkdnsrr(array_pop(explode("@", $email)), "MX"));
        }
        return true;
    }
    return false;
}

/*
 * IP
 */

function valid_ip($ip) {
    return (filter_var($ip, FILTER_VALIDATE_IP));
}

/*
 * URL
 */

function valid_url($str, $qstr = false) {
    $url = urldecode($str);
    if ($qstr === true) {
        return (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED));
    } else {
        return (filter_var($url, FILTER_VALIDATE_URL));
    }
}

/*
 * Load DOM Document
 */

function dom_load($html) {
    ob_start();
    ob_end_clean();
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->strictErrorChecking = FALSE;

    if ($dom->loadHTML($html)) {
        libxml_clear_errors();
        return $dom;
    }
    return false;
}

/*
 * Get All Meta Tags
 */

function meta_tags($html) {
    $metaTags = get_meta_tags($html);
    if ($load = dom_load($html)) {

        $title = $load->getElementsByTagName('title');
        $metaTags['title'] = $title->item(0)->nodeValue;

        foreach ($load->getElementsByTagName('meta') as $node) {
            if (preg_match("@og:+([a-z-_])+@", $node->getAttribute('property'))) {
                $metaTags[$node->getAttribute('property')] = $node->getAttribute('content');
            }
        }
        return $metaTags;
    }
}

/*
 * Get FavIcon
 */

function favicon($html) {
    $matches = '';
    if ($load = dom_load($html)) {
        foreach ($load->getElementsByTagName('link') as $node) {

            if (strtolower($node->getAttribute('rel')) === 'icon' ||
                    strtolower($node->getAttribute('rel')) === 'shortcut icon') {

                $matches = $node->getAttribute('href');
                break;
            }
        }
        return $matches;
    }
}

/*
 * Get `a` Tag Link
 */

function hrefs($html, $num = 'all') {
    $matches = [];
    if ($load = dom_load($html)) {
        foreach ($load->getElementsByTagName('a') as $node) {
            $matches[] = $node->getAttribute('href');
        }
        if ($num === 'all') {
            return $matches;
        } else {
            if (isset($matches[$num])) {
                return $matches[$num];
            }
            return false;
        }
    }
    return false;
}

/*
 * Get Script Tag Link
 */

function scripts($html, $num = 'all') {
    $matches = [];
    if ($load = dom_load($html)) {
        foreach ($load->getElementsByTagName('script') as $node) {
            $matches[] = $node->getAttribute('src');
        }
        if ($num === 'all') {
            return $matches;
        } else {
            if (isset($matches[$num])) {
                return $matches[$num];
            }
            return false;
        }
    }
    return false;
}

/*
 * Get Css Link
 */

function styles($html, $num = 'all') {
    $matches = '';
    if ($load = dom_load($html)) {
        foreach ($load->getElementsByTagName('link') as $node) {

            if ($node->getAttribute('rel') == 'stylesheet') {
                $matches[] = $node->getAttribute('href');
            }
        }
        if ($num === 'all') {
            return $matches;
        } else {
            if (isset($matches[$num])) {
                return $matches[$num];
            }
            return false;
        }
    }
    return false;
}

/*
 * PHP Comment Read
 */

function comment_read($file_name, $count = 'all') {
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
    }
    return false;
}

/*
 * Variable Clean
 */

function var_clean($force = false) {
    foreach (array_keys(get_defined_vars()) as $var) {

        if ($force === false) {
            if ($var === 'GLOBALS' || $var === '_POST' || $var === '_GET' || $var === '_COOKIE' ||
                    $var === '_FILES' || $var === '_REQUEST' || $var === '_SERVER' || $var === '_ENV') {

                continue;
            }
        }

        $var = null;
        unset($var);
    }
    clearstatcache();
}

/*
 * String to Hex
 */

function str2hex($string) {
    $hex = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0' . $hexCode, -2);
    }
    return strToUpper($hex);
}

/*
 * Hex to String
 */

function hex2str($hex) {
    $string = '';
    for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
        $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
    }
    return $string;
}

/*
 * Format Intizer Length
 */

function length_mk($val) {
    if ($val > 1000) {
        $val = round(($val / 1000), 2);
        $val = ($val > 1000) ? round(($val / 1000), 2) . 'M' : $val . 'K';
    }
    return $val;
}

/*
 * Format Size
 */

function format_bits($the_size) {
    switch ($the_size) {
        case ($the_size < 1024):
            return $the_size . 'B';
            break;

        case ($the_size >= 1024):
            return number_format($the_size / 1024, 2) . 'K';
            break;

        case ($the_size >= 1048576):
            return number_format($the_size / 1048576, 2) . 'M';
            break;

        case ($the_size >= 1073741824):
            return number_format($the_size / 1073741824, 2) . 'G';
            break;

        case ($the_size >= 1099511627776):
            return number_format($the_size / 1099511627776, 2) . 'T';
            break;

        default:
            return '0B';
            break;
    }
}


?>