<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbCustomer::readDiscount
 *
 * @package system\module
 */
class bbCustomer_readDiscount extends apiModule
{
    protected $rpcMethod = 'bbCustomer::readDiscount';

    /**
     * Set filter on cdid (customer-discount)
     *
     * @param $cdid
     * @return $this
     */
    public function setCdid($cdid)
    {
        return $this->addParam('cdid', (integer) $cdid);
    }

    /**
     * Set filter on customer-discount
     *
     * @param $cdid
     * @return $this
     */
    public function setCustomerDiscount($cdid)
    {
        return $this->setCdid($cdid);
    }

    /**
     * Set present
     *
     * @param $preset
     * @return $this
     */
    public function setPreset($preset)
    {
        return $this->addParam('preset', $preset);
    }
}