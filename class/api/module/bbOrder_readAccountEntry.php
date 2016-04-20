<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbOrder::readAccountEntry
 *
 * @package system\module
 */
class bbOrder_readAccountEntry extends apiModule
{
    protected $rpcMethod = 'bbOrder::readAccountEntry';

    /**
     * Set filter on account-nr.
     *
     * @param $accnr
     * @return $this
     */
    public function setAccnr($accnr)
    {
        return $this->addParam('accnr', (integer) $accnr);
    }

    /**
     * Set filter on account-id
     *
     * @param $aeid
     * @return $this
     */
    public function setAeid($aeid)
    {
        return $this->addParam('aeid', (integer) $aeid);
    }

    /**
     * Set filter on customer-id
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
     *
     * @param $ceid
     * @return $this
     */
    public function setCustomerId($ceid)
    {
        return $this->setCeid($ceid);
    }

    /**
     * Set filter on order-id
     *
     * @param $oeid
     * @return $this
     */
    public function setOeid($oeid)
    {
        return $this->addParam('oeid', (integer) $oeid);
    }

    /**
     * Set filter on settlement aeid
     *
     * @param $aeid
     * @return $this
     */
    public function setSettlementAeid($aeid)
    {
        return $this->addParam('settlement_aeid', (integer) $aeid);
    }

    /**
     * Return Address
     *
     * @param $bool
     * @return $this
     */
    public function addReturnAddress($bool = true)
    {
        return $this->addParam('return_adress', $bool);
    }

    /**
     * Return phone encodet
     *
     * @param $bool
     * @return $this
     */
    public function addReturnPhoneEncodet($bool = true)
    {
        $this->addReturnAddress();
        return $this->addParam('return_phone_encodet', $bool);
    }

    /**
     * Return ordnr
     *
     * @param $bool
     * @return $this
     */
    public function addReturnOrdnr($bool = true)
    {
        return $this->addParam('return_ordnr', $bool);
    }

    /**
     * Return settlements
     *
     * @param $bool
     * @return $this
     */
    public function addReturnSettlements($bool = true)
    {
        return $this->addParam('return_settlements', $bool);
    }

    /**
     * Return customer number
     *
     * @param $bool
     * @return $this
     */
    public function addReturnCusnr($bool = true)
    {
        return $this->addParam('return_cusnr', $bool);
    }

    /**
     * Return customer
     *
     * @param $bool
     * @return $this
     */
    public function addReturnCustomer($bool = true)
    {
        return $this->addParam('return_customer', $bool);
    }

    /**
     * Return customer overview
     *
     * @param $bool
     * @return $this
     */
    public function addReturnCustomerOverview($bool = true)
    {
        $this->addReturnCustomer();
        return $this->addParam('return_customer_overview', $bool);
    }

    /**
     * Return items
     *
     * @param $bool
     * @return $this
     */
    public function addReturnItems($bool = true)
    {
        return $this->addParam('return_items', $bool);
    }

    /**
     * Return payinfo
     *
     * @param $bool
     * @return $this
     */
    public function addReturnPayinfo($bool = true)
    {
        $this->addReturnCustomer();
        return $this->addParam('return_payinfo', $bool);
    }

    /**
     * Return payinfo bank
     *
     * @param $bool
     * @return $this
     */
    public function addReturnPayinfoBank($bool = true)
    {
        $this->addReturnPayinfo();
        return $this->addParam('return_payinfo_bank', $bool);
    }

    /**
     * Return payment
     *
     * @param $bool
     * @return $this
     */
    public function addReturnPayment($bool = true)
    {
        $this->addReturnPayinfo();
        return $this->addParam('return_payment', $bool);
    }
}