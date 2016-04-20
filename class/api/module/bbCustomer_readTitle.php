<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbCustomer::readTitle
 *
 * @package system\module
 */
class bbCustomer_readTitle extends apiModule
{
    protected $rpcMethod = 'bbCustomer::readTitle';

    /**
     * Set filter on Customer-title
     *
     * @param $ctid
     * @return $this
     */
    public function setCtid($ctid)
    {
        return $this->addParam('ctid', (integer) $ctid);
    }

    /**
     * Set filter on customer-title
     *
     * @param $ctid
     * @return $this
     */
    public function setCustomerTitle($ctid)
    {
        return $this->setCtid($ctid);
    }

    /**
     * Set filter on value (This german informal equivalent stored in this record title)
     *
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        return $this->addParam('value', (string) $value);
    }
}