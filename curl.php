<?php

/*
 * cURL Function
 */
function curl($arr = []) {

    $params = [];
    $params[CURLOPT_URL] = $arr['url'];

    $params[CURLOPT_MAXREDIRS] = 10;
    if(isset($arr['max_redirect'])) {
        $params[CURLOPT_MAXREDIRS] = $arr['max_redirect'];
    }

    $params[CURLOPT_HEADER] = 1;
    if(isset($arr['header'])) {
        $params[CURLOPT_HEADER] = $arr['header'];
    }

    $params[CURLOPT_USERAGENT] = 'Mozilla/5.0 (X11; Linux x86_64; rv:63.0) Gecko/20100101 Firefox/63.0';
    if(isset($arr['useragent'])) {
        $params[CURLOPT_USERAGENT] = $arr['useragent'];
    }

    $params[CURLOPT_NOBODY] = 0;
    if(isset($arr['nobody'])) {
        $params[CURLOPT_NOBODY] = $arr['nobody'];
    }

    $params[CURLOPT_SSL_VERIFYPEER] = 0;
    if(isset($arr['ssl_verify'])) {
        $params[CURLOPT_SSL_VERIFYPEER] = $arr['ssl_verify'];
    }

    $params[CURLOPT_FOLLOWLOCATION] = 1;
    if(isset($arr['follow_location'])) {
        $params[CURLOPT_FOLLOWLOCATION] = $arr['follow_location'];
    }

    /*
     * GET Data Setup
     */
    if(isset($arr['data_get']) && is_array($arr['data_get'])) {
        $data_get = '?';

        foreach($arr['data_get'] as $key => $val) {
            $data_get .= $key . '=' . $val . '&';
        }

        $data_get = trim($data_get, '&');
        $params[CURLOPT_URL] = $arr['url'] . $data_get;
    }

    /*
     * Method only for (GET & POST)
     */
    if(isset($arr['method'])) {

        if($arr['method'] === 'GET') {
            $params[CURLOPT_HTTPGET] = true;

        } elseif($arr['method'] === 'POST') {

            $params[CURLOPT_POST] = true;
            $params[CURLOPT_RETURNTRANSFER] = true;

            /*
             * POST Data Setup
             */
            if(isset($arr['data_post']) && is_array($arr['data_post'])) {
                $data_post = '';

                foreach($arr['data_post'] as $key => $val) {
                    $data_post .= $key . '=' . $val . '&';
                }

                $data_post = trim($data_post, '&');
                $params[CURLOPT_POSTFIELDS] = $data_post;
            }
        }
    }

    /*
     * Calling cURL
     */
    $ch = curl_init();
    $setopt = $params;

    /*
     * Marge Additional Array
     */
    if(isset($arr['params']) && is_array($arr['params'])) {
        $setopt = array_merge($params, $arr['params']);
    }

    /*
     * cURL SetOpt
     */
    curl_setopt_array($ch, $setopt);
    $exec = curl_exec($ch);

    /*
     * Return Data
     */
    $r = [];
    $r['code'] = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $r['response'] = $exec;
    $r['info'] = '';

    /*
     * Getting Response
     */
    if($exec && $r['code'] > 199 && $r['code'] < 400) {

        if(isset($arr['success']) && is_callable($arr['success'])) {
            $arr['success']($r);
        }
    } else {
        $r['info'] = curl_errno($ch);

        if(isset($arr['error']) && is_callable($arr['error'])) {
            $arr['error']($r);
        }
    }

    curl_close($ch);
}
