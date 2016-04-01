<?php

namespace www1601com\df_rp\api\module;
use www1601com\df_rp\api\apiModule;
use www1601com\df_rp\system\module\log;

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
        $this->system->rpc->setUrl($rp2InstanceUrl);
        $userId = $this->system->rpc->auth($rp2ApiUser, $rp2ApiPwd);
        if (!$userId)
        {
            log::warning("Faild to login as '$rp2ApiUser' from ".$_SERVER['REMOTE_ADDR'], $_SERVER);
            return false;
        }
        else
        {
            // https://doku.premium-admin.eu/doku.php/api/methoden/bbrpc/setutf8native
            $this->system->rpc->setUTF8Native(true);
            log::info("Successful login as '$rp2ApiUser' from ".$_SERVER['REMOTE_ADDR']);
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
        echo "Please authenticate with your RP2-Username and -Password.\n".$_SERVER['PHP_AUTH_USER'];
        exit;
    }
}