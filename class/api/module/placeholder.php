<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * This method can be used to work with not yet implemented api-methodes
 * @package system\module
 */
class placeholder extends apiModule
{
    public function setMethod($name)
    {
        $this->rpcMethod = $name;
        return $this;
    }

    public function addParam($name, $value)
    {
        return $this->addParam($name, $value);
    }
}