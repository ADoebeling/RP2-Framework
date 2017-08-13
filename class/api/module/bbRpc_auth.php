<?php

namespace rpf\api\module;
use rpf\api\apiModule;
use rpf\system\module\exception;
use rpf\system\module\log;

class bbRpc_auth extends apiModule
{
    /**
     * RPC-Auth
     *
     * If $username and $password are given, try to authenticate.
     * Else if $username and $password are not set, check if user is already logged in
     * Else if $fallback is 'httpAuth', try to authenticate by httpAuth
     * Else if $fallback is a object, try to authenticate by $fallback->auth()
     *
     * @param bool|string $username
     * @param bool|string $password
     * @param string|object $fallback
     * @return bool
     * @throws exception
     */
    public function auth($username = false, $password = false, $fallback = 'httpAuth')
    {
        // Try to authenticate
        if ($this->getApi()->getRpcSetUrl()->setUrl() && $username !== false && $password !== false)
        {
            log::setStopwatch();
            $this->rpcUserId  = \bbRpc::auth($username, $password);
            if ($this->rpcUserId != false)
            {
                if (isset($_SERVER['REMOTE_ADDR']))         $from = $_SERVER['REMOTE_ADDR'];
                else if (isset($_SERVER['SSH_CLIENT']))     $from = $_SERVER['SSH_CLIENT'];
                else if (isset($_SERVER['USER']))           $from = $_SERVER['USER'];
                else                                        $from = 'unknown';
                log::info("Login as $username (ID: {$this->rpcUserId}) from $from", __METHOD__."($username, *******)");
                $this->getApi()->getRpcMessages()->getMessages();
                return true;
            }
            else
            {
                log::warning("Login as $username FAILED from ".$_SERVER['REMOTE_ADDR'], __METHOD__."($username, *******)");
                $this->getApi()->getRpcMessages()->getMessages();
                return false;
            }

        }
        // User is already logged in
        else if ($this->rpcUserId !== false)
        {
            return true;
        }
        elseif ($fallback == 'httpAuth')
        {
            log::debug("Starting http-auth", __METHOD__);
            return $this->httpAuth();
        }
        elseif (is_object($fallback) && method_exists($fallback, 'auth'))
        {
            $fallback->auth($username, $password);
        }
        else
        {
            throw new exception('Auth failed', __METHOD__."$username, *******");
        }
    }

    public function httpAuth()
    {
        $user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : NULL;
        $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : NULL;
        $auth = $user != NULL ? $this->auth($user, $password) : false;
        return $auth ? true : static::sendHttpAuth();
    }

    protected static function sendHttpAuth()
    {
        header("WWW-Authenticate: Basic realm=\"Please enter your RP2-User and Password");
        header('HTTP/1.0 401 Unauthorized');
        \rpf\showError('Login', "Please authenticate with your RP²-Username and Password");
        exit;
    }

    public function __destruct()
    {
       $this->getApi()->getRpcLogout()->logout();
    }


}