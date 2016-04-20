<?php

namespace rpf\api\module;
use rpf\api\apiModule;
use rpf\api\bbRpc;
use rpf\system\module\exception;
use rpf\system\module\log;

class user extends apiModule {

    /**
     * Authorizes the given user at the api
     *
     * @param $rp2InstanceUrl
     * @param $rp2ApiUser
     * @param $rp2ApiPwd
     * @return bool
     */
    public function auth($rp2InstanceUrl, $rp2ApiUser, $rp2ApiPwd)
    {
        $duration = microtime(1);
        log::debug('Setting RPC-URL', "bbRpc::setUrl($rp2InstanceUrl)");
        bbRpc::setUrl($rp2InstanceUrl);
        $userId = bbRpc::auth($rp2ApiUser, $rp2ApiPwd);
        $duration = round(microtime(1)-$duration, 3);

        if (!$userId)
        {
            log::warning("Login failed from ".$_SERVER['REMOTE_ADDR']." within $duration sec.", "bbRpc::auth($rp2ApiUser, *****)", $_SERVER);
            $this->fetchRpcLog();
            return false;
        }
        else
        {
            // https://doku.premium-admin.eu/doku.php/api/methoden/bbrpc/setutf8native
            log::info("Login successful from ".$_SERVER['REMOTE_ADDR']." within $duration sec.", "bbRpc::auth($rp2ApiUser, *****)");
            $this->fetchRpcLog();

            log::debug('Set UTF-8', 'bbRpc::setUTF8Native(true)');
            bbRpc::setUTF8Native(true);
            $this->fetchRpcLog();
            return true;
        }
    }

    /**
     * Authenticates the user at the api by httpAuth
     * Only works if you're hosting this script on the same machine
     * as the rp2-instance, because the api-url is parsed out of the
     * home-path
     *
     * @return $this
     * @throws \Exception
     */
    public function httpAuth()
    {
        // Get the correct path on http AND ssh/bash
        $path = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PWD'];
        preg_match("/\\/.*\\/(\\d*)_\\d*\\/.*/", $path, $matches);
        $dfOrderNr = $matches[1];
        $rp2InstanceUrl = "http://$dfOrderNr.premium-admin.eu/";

        if ( !(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) &&
            $this->auth($rp2InstanceUrl, $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) )
        {
            static::sendHttpAuth($dfOrderNr);
        }

        return $this;
    }

    protected static function sendHttpAuth($dfOrderNr)
    {
        header("WWW-Authenticate: Basic realm=\"Please enter your RP2-User and Password for A$dfOrderNr\"");
        header('HTTP/1.0 401 Unauthorized');
        \rpf\showError('Login', "Please authenticate with your RP²-Username and Password for A$dfOrderNr");
        exit;
    }
}