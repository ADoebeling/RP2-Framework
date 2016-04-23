<?php

namespace rpf\api\module;
use rpf\api\apiModule;
use rpf\system\module\exception;
use rpf\system\module\log;

class bbRpc_getSid extends apiModule
{
    public function getSid($forceAuth = true)
    {
        if ($forceAuth)
        {
            $this->getApi()->getRpcAuth()->auth();
        }
        log::setStopwatch();
        $result = \bbRpc::getSid();
        log::debug("SID: $result", __METHOD__);
        return $result;
    }
}