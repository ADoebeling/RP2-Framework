<?php

namespace rpf\api\module;
use rpf\api\apiModule;

class bbRpc_logout extends apiModule
{
    public function logout()
    {
        return \bbRpc::logout();
    }

}