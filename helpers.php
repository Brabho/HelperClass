<?php

/*
 * Helper Class 
 * Custom & Mod Functions

 * Isset Variable
 */

function is_var(&$var) {
    return (isset($var) && $var !== null && $var !== '' && !empty($var) && strlen(trims($var)) > 0);
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
 * Get if Request by ajax (Return Bool)
 */

function is_ajax() {
    if (array_key_exists('HTTP_X_REQUESTED_WITH', get_all_headers()) ||
            array_key_exists('X-Requested-With', get_all_headers())) {

        return true;
    }
    return false;
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
            $param['file_name'] = ucwords(strtolower(basename($file)));
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
        header('Content-Length: ' . $param['length']);
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
        $srcfile = trims($src, '/') . '/' . $file;
        $destfile = trims($dest, '/') . '/' . $file;
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
    $info = finfo_file($finfo, $file);
    finfo_close($finfo);
    unset($finfo, $file);
    return $info;
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

function slug($text, $case = null, $charset = 'utf-8') {
    $text = htmlspecialchars($text, ENT_NOQUOTES, $charset);
    $text = preg_replace('~&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);~', '\1', $text);
    $text = preg_replace('~&([A-za-z]{2})(?:lig);~', '\1', $text);
    $text = preg_replace('~&[^;]+;~', '', $text);
    $text = preg_replace('~[\s!*\'();:@&=+$,/?%#[\]]+~', '-', $text);

    if (isset($case)) {
        if ($case === 'up') {
            return strtoupper($text);
        } elseif ($case === 'low') {
            return strtolower($text);
        }
    } else {
        return $text;
    }
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

            case 'url':
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

function encrypt($val, $arr = []) {
    if (!isset($arr['p'])) {
        $arr['p'] = 'PrimaryKey1';
    }
    if (!isset($arr['s'])) {
        $arr['s'] = 'SecondaryKey2';
    }
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

function decrypt($val, $arr = []) {
    if (!isset($arr['p'])) {
        $arr['p'] = 'PrimaryKey1';
    }
    if (!isset($arr['s'])) {
        $arr['s'] = 'SecondaryKey2';
    }
    $key = hash('sha256', $arr['p'] . $arr['s']);
    $val = explode('|', $val . '|');
    $deco = base64_decode($val[0]);
    $iv = base64_decode($val[1]);

    if (strlen($iv) === mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)) {
        $key = pack('H*', $key);
        $decry = trims(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $deco, MCRYPT_MODE_CBC, $iv));
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
 */

function rand_num($length = 40) {
    for ($i = -1; $i <= 4; $i++) {
        $bytes = openssl_random_pseudo_bytes(8, $crypto_strong);
        $num = hexdec(bin2hex($bytes));
    }
    $mtim = explode('.', microtime(true));
    $num = rand(1000000, 9999999) . $num . $mtim[0] . mt_rand(1000000, 9999999) . $mtim[1] . time();
    $num = preg_replace('/[^0-9]/', '', serialize($num));
    $num = str_shuffle($num);
    return substr($num, 0, $length);
}

/*
 * Random String
 * Default 40
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

function trims($content, $delmi = null, $white = null) {
    if (!isset($delmi)) {
        $delmi = " \t\n\r\0\x0B";
    }

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

function mod_strip_tags($text, $tags = '', $invert = false) {
    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trims($tags), $tags);
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
            return (preg_match_all('/^[a-zA-Z]+$/i', $alpha));
            break;

        case 'low':
            return (preg_match_all('/^[a-z]+$/', $alpha));
            break;

        case 'up':
            return (preg_match_all('/^[A-Z]+$/', $alpha));
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
 * Get All Headers
 */

function get_all_headers() {
    $headers = [];
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) === 'HTTP_') {
            $headers[str_ireplace(' ', '-', ucwords(strtolower(str_ireplace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
    return $headers;
}

/*
 * Load DOM Document
 */

function dom_load($html, $arr = []) {
    ob_start();
    ob_end_clean();

    if (!isset($arr['version'])) {
        $arr['version'] = null;
    }
    if (!isset($arr['charset'])) {
        $arr['charset'] = null;
    }

    $dom = new DOMDocument($arr['version'], $arr['charset']);

    if (isset($arr['white'])) {
        $dom->preserveWhiteSpace = $arr['white'];
    }

    if (isset($arr['format'])) {
        $dom->formatOutput = $arr['format'];
    }

    if (isset($arr['charset'])) {
        mb_convert_encoding($html, 'HTML-ENTITIES', $arr['charset']);
    }

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

function dom_metatags($html) {
    $metaTags = get_meta_tags($html);
    if ($load = dom_load(file_get_contents($html))) {

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

function dom_favicon($html) {
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

function dom_hrefs($html, $num = 'all') {
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
 * Get Script Tag Links
 */

function dom_scripts($html, $num = 'all', $type = 'text/javascript') {
    $matches = [];
    if ($load = dom_load($html)) {
        foreach ($load->getElementsByTagName('script') as $node) {
            if (strtolower($node->getAttribute('type')) === $type) {
                $matches[] = $node->getAttribute('src');
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
 * Get Css Links
 */

function dom_styles($html, $num = 'all') {
    $matches = '';
    if ($load = dom_load($html)) {
        foreach ($load->getElementsByTagName('link') as $node) {
            if (strtolower($node->getAttribute('rel')) === 'stylesheet' &&
                    strtolower($node->getAttribute('tyle')) === 'text/css') {

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

function get_comments($file_name, $count = 'all') {
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

/*
 * HTML, XHTML, XML Minify
 */

function xtml_min($content) {
    $search = [
        '/\r\n|\r|\n|\t|<!--(.*?)-->/si',
        '/\>[^\S ]+|> |>  |>   |\s+>/si',
        '/[^\S ]+\<| <|  <|   <|<\s+/si',
        '/(\s)+/si',
    ];
    $replace = ['', '> ', ' <', '\\1'];
    $content = preg_replace($search, $replace, $content);
    return trims($content);
}

/*
 * CSS Minify
 */

function css_min($css) {
    $search = [
        '!/\*[^*]*\*+([^/][^*]*\*+)*/!',
        '/\n|\r|\t|\r\n|  |   |    /',
    ];
    $css = preg_replace($search, '', $css);

    $search = ['/ {/s', '/ }/s', '/ :|: /s', '/ ,|, /s'];
    $replace = ['{', '}', ':', ','];
    $css = preg_replace($search, $replace, $css);
    $css = trims($css);
    return $css;
}

/*
 * JavaScript, PHP Minify
 */

function script_min($script) {
    $search = [
        '!/\*[^*]*\*+([^/][^*]*\*+)*/!',
        '/\n|\r|\t|\r\n|  |   |    /',
    ];

    $script = preg_replace($search, '', $script);
    $script = trims($script);
    return $script;
}

/*
 * JSON Minify
 */

function json_min($json) {
    $search = ['/[\p{Z}\s]{2,}/u'];
    $json = preg_replace($search, '', $json);
    $json = trims($json);
    return $json;
}

/*
 * Get User Referer
 */

function usr_referer() {
    if (array_key_exists('HTTP_REFERER', $_SERVER) &&
            $_SERVER['HTTP_REFERER'] !== null &&
            !empty($_SERVER['HTTP_REFERER'])) {

        return htm_en($_SERVER['HTTP_REFERER']);
    } elseif (array_key_exists('Referer', get_all_headers())) {

        return htm_en(get_all_headers()['Referer']);
    }
    return false;
}

/*
 * Get User IP
 */

function usr_ip() {
    if (is_arr($_SERVER, 'HTTP_CLIENT_IP')) {

        return htm_en($_SERVER['HTTP_CLIENT_IP']);
    } elseif (is_arr($_SERVER, 'HTTP_X_FORWARDED_FOR')) {

        return htm_en($_SERVER['HTTP_X_FORWARDED_FOR']);
    } elseif (is_arr($_SERVER, 'HTTP_X_REAL_IP')) {

        return htm_en($_SERVER['HTTP_X_REAL_IP']);
    } elseif (is_arr($_SERVER, 'REMOTE_ADDR')) {

        return htm_en($_SERVER['REMOTE_ADDR']);
    }

    return false;
}

/*
 * Get User Browser
 */

function usr_browser() {
    $browser = 'Other';
    $browser_arr = [
        '@msie@i' => 'Internet Explorer',
        '@Trident@i' => 'Internet Explorer',
        '@edge@i' => 'Edge',
        '@firefox@i' => 'Firefox',
        '@opr@i' => 'Opera',
        '@chrome@i' => 'Chrome',
        '@safari@i' => 'Safari',
        '@netscape@i' => 'Netscape',
        '@maxthon@i' => 'Maxthon',
        '@konqueror@i' => 'Konqueror',
        '@mobile@i' => 'Handheld Browser',
        '@UCBrowser|UCWEB@i' => 'UC Browser'
    ];
    foreach ($browser_arr as $regex => $value) {

        if (preg_match_all($regex, get_all_headers()['User-Agent'], $matchs)) {
            $browser = $value;
            break;
        }
    }

    unset($browser_arr, $regex, $value);
    return $browser;
}

/*
 * Get User OS
 */

function usr_os() {
    $os = 'Other';
    $os_arr = [
        '@windows nt 10@i' => 'Windows 10',
        '@windows nt 6.3@i' => 'Windows 8.1',
        '@windows nt 6.2@i' => 'Windows 8',
        '@windows nt 6.1@i' => 'Windows 7',
        '@windows nt 6.0@i' => 'Windows Vista',
        '@windows nt 5.2@i' => 'Windows Server 2003/XP x64',
        '@windows nt 5.1@i' => 'Windows XP',
        '@windows xp@i' => 'Windows XP',
        '@windows nt 5.0@i' => 'Windows 2000',
        '@windows me@i' => 'Windows ME',
        '@win98@i' => 'Windows 98',
        '@win95@i' => 'Windows 95',
        '@win16@i' => 'Windows 3.11',
        '@macintosh|mac os x@i' => 'Mac OS X',
        '@mac_powerpc@i' => 'Mac OS 9',
        '@ubuntu@i' => 'Ubuntu',
        '@Red Hat@i' => 'Red Hat',
        '@linux@i' => 'Linux',
        '@iphone@i' => 'iPhone',
        '@ipod@i' => 'iPod',
        '@ipad@i' => 'iPad',
        '@android@i' => 'Android',
        '@blackberry@i' => 'BlackBerry',
        '@webos@i' => 'Mobile'
    ];
    foreach ($os_arr as $regex => $value) {

        if (preg_match($regex, get_all_headers()['User-Agent'])) {
            $os = $value;
            break;
        }
    }

    unset($os_arr, $regex, $value);
    return $os;
}

/*
 * User GEO Location
 * API: geoplugin, ipinfo
 */

function usr_location($geo_api = 'geoplugin') {
    switch ($geo_api) {
        case 'geoplugin':
            $location = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . usr_ip()));
            break;

        case 'ipinfo':
            $location = json_decode(file_get_contents('http://ipinfo.io/' . usr_ip()), TRUE);
            break;
    }
    return $location;
}
