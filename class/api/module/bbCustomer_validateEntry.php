<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbCustomer::validateEntry
 * Are all entries valid it will return true, else it return false
 *
 * @return bool
 * @package system\module
 */
class bbCustomer_validateEntry extends apiModule
{
    protected $rpcMethod = 'bbCustomer::validateEntry';
}