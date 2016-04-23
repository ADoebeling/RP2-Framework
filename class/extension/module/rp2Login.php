<?php

namespace rpf\extension\module;
use rpf\extension\extensionModule;


/**
 * RP2-Direct-Login
 *
 * Caution: Doesn't work cause of creepy api-implementation of df:
 * You have to authenticate as admin to authenticate as user. WTF?!
 *
 * The RPC-SID can't be used as RP2-Login-Token :(
 *
 *
 * @package rpf\extension\module
 */
class rp2Login extends extensionModule
{
    /*public function httpAuth()
    {
        $token = $this->getApi()->getSessionAuthToken()->getAuthToken($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        if ( !(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && $token == NULL ))
        {
            static::sendHttpAuth();
            return false;
        }
        else
        {
            return $token;
        }
    }

    protected static function sendHttpAuth()
    {
        header("WWW-Authenticate: Basic realm=\"Please enter your RP2-User and Password");
        header('HTTP/1.0 401 Unauthorized');
        \rpf\showError('Login', "Please authenticate with your RP²-Username and Password");
        exit;
    }

    public function execute($param)
    {
        parent::execute($param);
        $token = $this->httpAuth();
        header("Redirect:".RPF_API_MODULE_BBRPC_SETURL_URL.$token);
    }*/

    public function execute($param)
    {
        parent::execute($param);
        require_once __DIR__.'/../../api/bb.rpc.php';
        $token = $this->getApi()->getRpcSid()->getSid();
        header("Location:".$this->getApi()->getRpcSetUrl()->getUrl().$token.'/');
    }
}