<?php

namespace rpf\api\module;
use rpf\api\apiModule;
use rpf\system\module\exception;

/**
 * Implementation of bbCustomer::saveAdress
 *
 * @package system\module
 */
class bbCustomer_saveAdress extends apiModule
{
    protected $rpcMethod = 'bbCustomer::readAdress';

    /**
     * Set primary-key on ctid customerId
     *
     * @param $ctid
     * @return $this
     * @throws exception
     * @todo Implement
     */
    public function setCtid($ctid)
    {
        throw new exception(__METHOD__.' is not implemented yet');
    }

    /**
     * Set primary-key on typ
     *
     * @param $typ (enum)
     * @return $this
     */
    public function setTyp($typ)
    {
        return $this->addParam('typ', $typ);
    }

    /**
     * Return as an array
     *
     * @param $adress1
     * @return $this
     */
    public function addAdress1($adress1)
    {
        return $this->addParam('adress_1', (string) $adress1);
    }
}