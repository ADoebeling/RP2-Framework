<?php

namespace rpf\api\module;
use rpf\api\apiModule;

class bbRpc_auth extends apiModule
{
    public function auth($sUsername, $sPassword)
    {

    }

    protected function __destruct()
    {
       $this->getApi()->getRpcLogout->logout();
    }


}