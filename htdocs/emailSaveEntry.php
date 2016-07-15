<?php
/**
 * RP2 Password-Sniffer for mail-module
 *
 * @Author Andreas Doebeling
 * @Link https://github.com/ADoebeling
 * @Link http://www.doebeling.me
 */
header('Content-type: text/html; charset=ISO-8859-1'); // Important!
require_once '../emailSaveEntry.config.php';

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
function returnRequest($url, array $data, array $headers = null) {
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
    $ctx = stream_context_create($params);
    $fp = fopen($url, 'rb', false, $ctx);
    if ($fp) {
        return stream_get_contents($fp);
    } else {
        return false;
    }
}

function getDb($dbHost = DB_HOST, $dbName = DB_NAME, $dbUser = DB_USER, $dbPwd = DB_PWD)
{
    return new mysqli($dbHost, $dbUser, $dbPwd, $dbName);
}

function storeAccount(mysqli $mysql, $seid, $mail, $password, $comment = '')
{
    $sql = "INSERT INTO mail SET seid='$seid', mail='$mail', password='$password', date=NOW(), comment='$comment'";
    return $mysql->query($sql);
}

$result = returnRequest(RPF_URL.$_SERVER['SCRIPT_URL'], $_POST, ['Cookie' => 'bid='.$_COOKIE['bid'].';']);
$resultObject = json_decode(utf8_encode($result));

$e = !isset($_POST['entry']) ?: $_POST['entry'];

if (isset($e['seid']) && isset($e['password']) && isset($e['addresses'][0]['name']) &&
    ($resultObject->_status[0]->typ == 'ok' || $resultObject->action == 'df_prices'))
{
    $db = getDb();
    storeAccount($db, $e['seid'], $e['addresses'][0]['name'], $e['password'], 'TESTING by AD');

    if (isset($resultObject->_status[0])) { // Exchange order
        $feedback = new stdClass();
        $feedback->typ = 'warn';
        $feedback->msg = 'RP2-Hack: Das Plain-Text-Passwort wurde per E-Mail versendet, separat gespeichert und archiviert.';
        $resultObject->_status[] = $feedback;
        $result = utf8_decode(json_encode($resultObject));
    }

    $text = sprintf(RPF_MAIL_TEXT, $e['addresses'][0]['name'], $e['storage']['size'], $e['password']);
    $subject = sprintf(RPF_MAIL_SUBJECT, $e['addresses'][0]['name']);
    mail($e['addresses'][0]['name'], $subject, $text, "FROM: ".RPF_MAIL_FROM."\nCC: ".RPF_MAIL_CC."\nREPLY-TO: ".RPF_MAIL_REPLY);

    echo $result;



}
else
{
    mail(RPF_MAIL_DEBUG, "[RPF/emailSaveEntry] ERROR", print_r($_POST, 1).print_r($resultObject, 1));
    if (isset($resultObject->_status[0])) { // Exchange order
        $feedback = new stdClass();
        $feedback->typ = 'err';
        $feedback->msg = 'RP2-Hack: Something went wrong. Ask @ADoebeling';
        $resultObject->_status[] = $feedback;
        $result = utf8_decode(json_encode($resultObject));
    }
    echo $result;
}