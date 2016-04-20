<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbCustomer::readEntry
 *
 * @package system\module
 */
class bbCustomer_readEntry extends apiModule
{
    protected $rpcMethod = 'bbCustomer::readEntry';

    /**
     * Set filter on ctid (customerId)
     *
     * @param $ceid
     * @return $this
     */
    public function setCeid($ceid)
    {
        return $this->addParam('ceid', (integer) $ceid);
    }

    /**
     * Set filter on customer-id
     * (alias for $this setCeid())
     *
     * @param $ceid
     * @return $this
     */
    public function setCustomerId($ceid)
    {
        return $this->setCeid($ceid);
    }

    /**
     * Set filter on uid (User-iD)
     *
     * @param $uid
     * @return $this
     */
    public function setUid($uid)
    {
        return $this->addParam('uid', $uid);
    }

    /**
     * Set filter on user-id
     * (alias for $this setUid())
     *
     * @param $uid
     * @return $this
     */
    public function setUserId($uid)
    {
        return $this->setUid($uid);
    }

    /**
     * Set filter on newsletter
     *
     * @param $newsletter
     * @return $this
     */
    public function setNewsletter($newsletter = true)
    {
        return $this->addParam('newsletter',(bool) $newsletter);
    }

    /**
     * Return address
     *
     * @param $bool
     * @return $this
     * @link https://github.com/ADoebeling/RP2-Framework/issues/31
     */
    public function addReturnAddress($bool = true)
    {
        //Misspelling!
        return $this->addParam('return_adress', (bool) $bool);
    }

    /**
     * Return adress-country
     *
     * @param $bool
     * @return $this
     */
    public function addReturnAddressCountry($bool= true)
    {
        $this->addParam('return_adress', (bool) $bool);
        return $this->addParam('return_adress_country', (bool) $bool);
    }

    /**
     * Return orders
     *
     * @param $bool
     * @return $this
     */
    public function addReturnOrders($bool = true)
    {
        return $this->addParam('return_orders', (bool) $bool);
    }

    /**
     * Return account_entrys -> Rechnungen des Kunden
     * need return_orders
     *
     * @param $bool
     * @return $this
     * @link https://github.com/ADoebeling/RP2-Framework/issues/32
     */
    public function addReturnAccountEntries($bool = true)
    {
        $this->addParam('return_orders', (bool) $bool);
        return $this->addParam('return_account_entrys', (bool) $bool);
    }

    /**
     * Return settlements -> Buchungen
     *
     * @param $bool
     * @return $this
     */
    public function addReturnSettlements($bool = true)
    {
        $this->addParam('return_account_entrys', (bool) $bool);
        return $this->addParam('return_account_entrys', (bool) $bool);
    }

    /**
     * Return limits
     *
     * @param $bool
     * @return $this
     */
    public function addReturnLimits($bool = true)
    {
        $this->addParam('return_orders', (bool) $bool);
        return $this->addParam('return_limits', (bool) $bool);
    }

    /**
     * Return tariff
     *
     * @param $bool
     * @return $this
     */
    public function addReturnTariff($bool = true)
    {
        $this->addParam('return_orders', (bool) $bool);
        return $this->addParam('return_tariff', (bool) $bool);
    }

    /**
     * Return user
     *
     * @param $bool
     * @return $this
     */
    public function addReturnUser($bool = true)
    {
        return $this->addParam('return_user', (bool) $bool);
    }

    /**
     * Return policy
     *
     * @param $bool
     * @return $this
     */
    public function addReturnPolicy($bool = true)
    {
        $this->addParam('return_user', (bool) $bool);
        return $this->addParam('return_policy', (bool) $bool);
    }

    /**
     * Return handles
     *
     * @param $bool
     * @return $this
     */
    public function addReturnHandles($bool = true)
    {
        return $this->addParam('return_handles', (bool) $bool);
    }

    /**
     * Return staid
     *
     * @param $bool
     * @return $this
     */
    public function addReturnStaid($bool = true)
    {
        return $this->addParam('return_staid', (bool) $bool);
    }

    /**
     * Return overview
     *
     * @param $bool
     * @return $this
     */
    public function addReturnOverview($bool = true)
    {
        return $this->addParam('return_overview', (bool) $bool);
    }

    /**
     * Return pay-info
     *
     * @param $bool
     * @return $this
     */
    public function addReturnPayInfo($bool = true)
    {
        return $this->addParam('return_payinfo', (bool) $bool);
    }

    /**
     * Return pay-info_bank
     *
     * @param $bool
     * @return $this
     */
    public function addReturnPayInfoBank($bool = true)
    {
        $this->addParam('return_payinfo', (bool) $bool);
        return $this->addParam('return_payinfo_bank', (bool) $bool);
    }

    /**
     * Return payment
     *
     * @param $bool
     * @return $this
     */
    public function addReturnPayment($bool = true)
    {
        $this->addParam('return_payinfo', (bool) $bool);
        return $this->addParam('return_payment', (bool) $bool);
    }

    /**
     * Return phone_encodet
     *
     * @param $bool
     * @return $this
     */
    public function addReturnPhoneEncodet($bool = true)
    {
        $this->addParam('return_adress', (bool) $bool);
        return $this->addParam('return_phone_encodet', (bool) $bool);
    }
}