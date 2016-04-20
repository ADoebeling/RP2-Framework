<?php

namespace rpf\api\module;
use rpf\api\apiModule;
use rpf\system\module\exception;

/**
 * Implementation of bbCustomer::saveAdress
 *
 * @package system\module
 */
class bbCustomer_readPayment extends apiModule
{
    protected $rpcMethod = 'bbCustomer::readPayment';

    /**
     * Set filter on cpid (id of payment)
     *
     * @param $cpid
     * @return $this
     */
    public function setCpid($cpid)
    {
        return $this->addParam('cpid', (integer) $cpid);
    }

    /**
     * Set filter on cpid (id of payment)
     *
     * @param $cpid
     * @return $this
     */
    public function setPaymentId($cpid)
    {
        return $this->setCpid($cpid);
    }

    /**
     * Set filter on preset (advance selected value)
     *
     * @param $preset
     * @return $this
     */
    public function setPreset($preset = true)
    {
        return $this->addParam('preset', (bool) $preset);
    }

    /**
     * Set filter on typ
     *
     * @param $typ (enum)
     * @return $this
     */
    public function setTyp($typ)
    {
        return $this->addParam('typ', $typ);
    }
}