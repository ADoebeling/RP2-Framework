<?php

namespace rpf\api\module;
use rpf\api\apiModule;

class bbRpc_setUTF8Native extends apiModule
{
    public function setUTF8Native($bUTF8 = 1)
    {
        return \bbRpc::setUTF8Native();
    }

}