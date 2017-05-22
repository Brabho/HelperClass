<?php

/*
 * Visitor Details
 */

class visitor {
    /*
     * Get Header
     */

    public function header($param = NULL) {
        $allheaders = getallheaders();
        if (isset($param)) {
            return (isset($allheaders[$param])) ? htmlspecialchars($allheaders[$param], ENT_QUOTES, 'UTF-8') : false;
        } else {
            return $allheaders;
        }
    }

    /*
     * Get Referer
     */

    public function referer() {
        if (array_key_exists('HTTP_REFERER', $_SERVER) && $_SERVER['HTTP_REFERER'] !== null && !empty($_SERVER['HTTP_REFERER'])) {
            return htmlspecialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES, 'UTF-8');
        } elseif ($this->header('Referer')) {
            return $this->header('Referer');
        }
        return false;
    }

    /*
     * Get IP
     */

    public function ip() {
        if (array_key_exists('HTTP_CLIENT_IP', $_SERVER) && $_SERVER['HTTP_CLIENT_IP'] !== null && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            return htmlspecialchars($_SERVER['HTTP_CLIENT_IP'], ENT_QUOTES, 'UTF-8');
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR'] !== null && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR'], ENT_QUOTES, 'UTF-8');
        } elseif (array_key_exists('HTTP_X_REAL_IP', $_SERVER) && $_SERVER['HTTP_X_REAL_IP'] !== null && !empty($_SERVER['HTTP_X_REAL_IP'])) {
            return htmlspecialchars($_SERVER['HTTP_X_REAL_IP'], ENT_QUOTES, 'UTF-8');
        } elseif (array_key_exists('REMOTE_ADDR', $_SERVER) && $_SERVER['REMOTE_ADDR'] !== null && !empty($_SERVER['REMOTE_ADDR'])) {
            return htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, 'UTF-8');
        }
        return false;
    }

    /*
     * Get Browser
     */

    public function browser() {
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

            if (preg_match_all($regex, $this->header('User-Agent'), $matchs)) {
                $browser = $value;
                break;
            }
        }

        unset($browser_arr, $regex, $value);
        return $browser;
    }

    /*
     * Get OS
     */

    public function os() {
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
            '@linux@i' => 'Linux',
            '@ubuntu@i' => 'Ubuntu',
            '@Red Hat@i' => 'Red Hat',
            '@iphone@i' => 'iPhone',
            '@ipod@i' => 'iPod',
            '@ipad@i' => 'iPad',
            '@android@i' => 'Android',
            '@blackberry@i' => 'BlackBerry',
            '@webos@i' => 'Mobile'
        ];
        foreach ($os_arr as $regex => $value) {

            if (preg_match($regex, $this->header('User-Agent'))) {
                $os = $value;
                break;
            }
        }

        unset($os_arr, $regex, $value);
        return $os;
    }

    /*
     * Get if Request by ajax (Return Bool)
     */

    public function is_ajax() {
        if ($this->header('HTTP_X_REQUESTED_WITH') && $this->header('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest') {
            return true;
        } elseif ($this->header('X-Requested-With') && $this->header('X-Requested-With') === 'XMLHttpRequest') {
            return true;
        }
        return false;
    }

    /*
     * Check Visitor is bot (Return Bool)
     */

    public function is_bot() {
        return ($this->header('User-Agent') && preg_match('/bot|crawl|slurp|spider/i', $this->header('User-Agent')));
    }

    /*
     * GEO Location
     * API: geoplugin, ipinfo
     */

    public function location($geo_api = 'geoplugin') {
        if ($geo_api === 'geoplugin') {
            $location = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $this->ip()));
        } elseif ($geo_api === 'ipinfo') {
            $location = json_decode(file_get_contents('http://ipinfo.io/' . $this->ip()), TRUE);
        }
        return $location;
        unset($geo_api, $location);
    }

    /*
     * All in one 
     * Slow *
     */

    public function all_details() {
        $details = $this->header();
        $details['referer'] = ($this->referer()) ? $this->referer() : 'none';
        $details['ip'] = $this->ip();
        $details['browser'] = $this->browser();
        $details['os'] = $this->os();
        $details['ajax'] = ($this->is_ajax()) ? 'true' : 'false';
        $details['bot'] = ($this->is_bot()) ? 'true' : 'false';
        $details['location']['geoplugin'] = $this->location('geoplugin');
        $details['location']['ipinfo'] = $this->location('ipinfo');
        return $details;
    }

}