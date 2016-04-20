<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbCustomer::readAdress
 *
 * @package system\module
 */
class bbCustomer_readAddress extends apiModule
{
    protected $rpcMethod = 'bbCustomer::readAdress';

    /**
     * Set filter on customer-id
     *
     * @param $ceid
     * @return $this
     */
    public function setCeid($ceid)
    {
        return $this->addParam('ceid', $ceid);
    }

    /**
     * Set filter on customer-id
     *
     * @param $ceid
     * @return $this
     */
    public function setCustomerId($ceid)
    {
        return $this->setCeid($ceid);
    }

    /**
     * Set filter on typ
     *
     * @param $typ
     * @return $this
     */
    public function setTyp($typ)
    {
        return $this->addParam('typ', $typ);
    }

    /**
     * @todo
     *
     * @param bool $bool
     * @return $this
     */
    public function addCountry($bool = true)
    {
        return $this->addParam('return_country', (bool) $bool);
    }

    /**
     * Return the phone-number encodet
     *
     * @param bool $bool
     * @return $this
     */
    public function addReturnPhoneEncodet($bool = true)
    {
        return $this->addParam('return_phone_encodet', (bool) $bool);
    }
}