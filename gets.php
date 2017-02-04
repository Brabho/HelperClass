<?php

class gets {
    /*
     * Get Mime Type
     */

    public function mime($file) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        return finfo_file($finfo, $file);
        finfo_close($finfo);
        unset($file, $finfo);
    }

    /*
     * Uri Information
     */

    public function uri_info($link) {
        $allarr = parse_url($link);
        $domain = (!array_key_exists('host', $allarr)) ? $allarr['path'] : $allarr['host'];

        $allarr['query'] = explode('&', $allarr['query']);
        $allarr['ip'] = gethostbyname($domain);
        return $allarr;
        unset($link, $allarr, $domain);
    }

    /*
     * Get Email, Image, Link By Regex
     */

    public function byPrtn($subject, $count = 'all', $pattern = null, $by = 'email') {
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
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        unset($subject, $count, $pattern, $by);
    }

    /*
     * Time Ago Function
     */

    public function timeAgo($time) {
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
        unset($time, $periods, $lengths, $now, $difference, $j);
    }

    /*
     * Format Intizer Length
     */

    public function lengthK($val) {
        if ($val > 1000) {
            $val = round(($val / 1000), 2);
            $val = ($val > 1000) ? round(($val / 1000), 2) . 'M' : $val . 'K';
        }
        return $val;
        unset($val);
    }

    /*
     * Format Size
     */

    public function formatBits($the_size) {
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
        unset($the_size);
    }

    public function phpParse() {
        ob_start();
        phpinfo(INFO_MODULES);
        $s = ob_get_contents();
        ob_end_clean();
        $s = strip_tags($s, '<h2><th><td>');
        $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/', '<info>\1</info>', $s);
        $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/', '<info>\1</info>', $s);
        $t = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
        $r = array();
        $count = count($t);
        $p1 = '<info>([^<]+)<\/info>';
        $p2 = '/' . $p1 . '\s*' . $p1 . '\s*' . $p1 . '/';
        $p3 = '/' . $p1 . '\s*' . $p1 . '/';
        for ($i = 1; $i < $count; $i++) {
            if (preg_match('/<h2[^>]*>([^<]+)<\/h2>/', $t[$i], $matchs)) {
                $name = trim($matchs[1]);
                $vals = explode("\n", $t[$i + 1]);
                foreach ($vals AS $val) {
                    if (preg_match($p2, $val, $matchs)) {
                        $r[$name][trim($matchs[1])] = array(trim($matchs[2]), trim($matchs[3]));
                    } elseif (preg_match($p3, $val, $matchs)) {
                        $r[$name][trim($matchs[1])] = trim($matchs[2]);
                    }
                }
            }
        }
        return $r;
        unset($s, $t, $r, $count, $p1, $p2, $p3, $i);
    }

    function __destruct() {
        unset($this);
    }

}

?>