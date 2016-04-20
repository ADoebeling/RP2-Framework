<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbOrder::readAccountAddress
 *
 * @package system\module
 */
class bbOrder_readAccountAddress extends apiModule
{
    protected $rpcMethod = 'bbOrder::readAccountAdress';

    /**
     * Set filter on aeid
     *
     * @param $aeid
     * @return $this
     */
    public function setAeid($aeid)
    {
        return $this->addParam('aeid', $aeid);
    }

    /**
     * Return Customer
     *
     * @param $bool
     * @return $this
     */
    public function addReturnCustomer($bool)
    {
        return $this->addParam('return_customer', $bool);
    }

    /**
     * Return phone encodet
     *
     * @param $bool
     * @return $this
     */
    public function addReturnPhoneEncodet($bool)
    {
        return $this->addParam('return_phone_encodet', $bool);
    }
}