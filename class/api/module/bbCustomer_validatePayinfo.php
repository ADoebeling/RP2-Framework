<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbCustomer::validatePayinfo
 * Are all entries valid it will return true, else it return false
 *
 * @return bool
 * @package system\module
 */
class bbCustomer_validatePayinfo extends apiModule
{
    protected $rpcMethod = 'bbCustomer::validatePayinfo';
}