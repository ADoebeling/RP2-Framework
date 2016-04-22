<?php

namespace rpf\api\module;
use rpf\api\apiModule;

class bbRpc_setUrl extends apiModule
{
    public function setUrl($sPathToSystem)
    {
        return \bbRpc::setUrl($sPathToSystem);
    }

}