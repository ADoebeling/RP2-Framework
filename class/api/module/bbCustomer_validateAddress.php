<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbCustomer::validateAddress
 * Are all entries valid it will return true, else it return false
 *
 * @return bool
 * @package system\module
 */
class bbCustomer_validateAddress extends apiModule
{
    protected $rpcMethod = 'bbCustomer::validateAdress';
}