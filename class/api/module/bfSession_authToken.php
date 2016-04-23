<?php

namespace rpf\api\module;
use rpf\api\apiModule;
use rpf\system\module\exception;
use rpf\system\module\log;

class bfSession_authToken extends apiModule
{
    protected $rpcMethod = 'bfSession::createAuthToken';

    public function getAuthToken($username, $password)
    {
        $this->addParam('name', $username);
        $this->addParam('password', $password);
        return $this->getApi()->getRpcCall()->call($this->rpcMethod, $this->rpcParams, false, false);
    }
}