<?php

/*
 * cURL Function
 */

function c_url($arr = [], $ext_func = null, $callback = null) {

    if (!isset($arr['timeout'])) {
        $arr['timeout'] = 300;
    }

    if (!isset($arr['max_redirect'])) {
        $arr['max_redirect'] = 10;
    }

    if (!isset($arr['header'])) {
        $arr['header'] = 1;
    }

    if (!isset($arr['nobody'])) {
        $arr['nobody'] = 0;
    }

    if (!isset($arr['ssl_verify'])) {
        $arr['ssl_verify'] = 0;
    }

    if (!isset($arr['follow_location'])) {
        $arr['follow_location'] = 1;
    }

    if (!isset($arr['method'])) {
        $arr['method'] = 'GET';
    }

    if (!isset($arr['http_status_arr'])) {
        $arr['http_status_arr'] = ['200', '201', '202', '301', '302', '307', '308'];
    }

    $return_data = [];
    $header_things = [];

    /*
     * Calling cURL
     */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $arr['url']);

    /*
     * Proxy IP & Port
     */
    if (isset($arr['proxy'])) {

        if (isset($arr['proxy'][0])) {
            $proxy_ip = $arr['proxy'][0];
        } else {
            $proxy_ip = $arr['proxy'];
        }

        if (isset($arr['proxy']['interface'])) {
            curl_setopt($ch, CURLOPT_INTERFACE, $proxy_ip);
            curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
        }

        array_push($header_things, 'HTTP_CLIENT_IP: ' . $proxy_ip);
        array_push($header_things, 'HTTP_X_FORWARDED_FOR: ' . $proxy_ip);
        array_push($header_things, 'HTTP_X_REAL_IP: ' . $proxy_ip);
        array_push($header_things, 'REMOTE_ADDR: ' . $proxy_ip);

        if (isset($arr['proxy']['port'])) {
            curl_setopt($ch, CURLOPT_PROXYPORT, $arr['proxy']['port']);
        }
    }

    /*
     * Adding Host
     */
    if (isset($arr['host'])) {
        array_push($header_things, 'HOST: ' . $arr['host']);
    }

    /*
     * Additional Headers
     */
    if (isset($arr['headers'])) {
        foreach ($arr['headers'] as $headers) {
            array_push($header_things, $headers);
        }
    }

    /*
     * Setup Header
     */
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header_things);

    /*
     * Set User Agent
     */
    if (isset($arr['agent'])) {
        curl_setopt($ch, CURLOPT_USERAGENT, $arr['agent']);
    }

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $arr['timeout']);
    curl_setopt($ch, CURLOPT_MAXREDIRS, $arr['max_redirect']);
    curl_setopt($ch, CURLOPT_HEADER, $arr['header']);
    curl_setopt($ch, CURLOPT_NOBODY, $arr['nobody']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $arr['ssl_verify']);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $arr['follow_location']);

    /*
     * Calling Additional Functions
     */
    if (isset($ext_func)) {
        $ext_func($ch);
    }

    if ($arr['method'] === 'POST' && isset($arr['data'])) {
        /*
         * POST Reuqest
         */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr['data']);
    } else {
        /*
         * GET Reuqest
         */
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
    }

    $output = curl_exec($ch);
    $return_data['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    /*
     * Getting Response
     */
    if ($output === false) {

        $return_data[0] = 'error';
        $return_data['response'] = 'SERVER';
        $return_data['error'] = curl_errno($ch);
    } else {

        if ($arr['header'] === 1) {
            $return_data['header'] = preg_replace('@<(.*)>@i', '', $output);

            $status_code = explode("\n", $return_data['header'])[0];
            $status_code = preg_replace('@[^0-9]@i', '', $status_code);
            $return_data['status_code'] = substr($status_code, 2, 3);
        }

        /*
         * Checking HTTP Status Code
         */
        if (isset($return_data['status_code']) && in_array($return_data['status_code'], $arr['http_status_arr'])) {

            $return_data[0] = 'success';
            $return_data['response'] = $output;
        } elseif (in_array($return_data['status'], $arr['http_status_arr'])) {

            $return_data[0] = 'success';
            $return_data['response'] = $output;
        } else {

            $return_data[0] = 'error';
            $return_data['response'] = 'HEADER';
        }
    }

    curl_close($ch);
    $callback($return_data);
}
