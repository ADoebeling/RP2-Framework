<?php

namespace rpf\api\module;
use rpf\api\apiModule;
use rpf\system\module\exception;

/**
 * Implementation of bbOrder::readEntry
 *
 * @package system\module
 */
class bbOrder_readEntry extends apiModule
{
    protected $rpcMethod = 'bbOrder::readEntry';

    /**
     * Set primary-key on customerId
     *
     * @param $ceid
     * @return $this
     */
    public function setCeid($ceid)
    {
        return $this->addParam('ceid', (integer) $ceid);
    }

    /**
     * Set primary-key on customerId
     *
     * @param $ceid
     * @return $this
     */
    public function setCustomerId($ceid)
    {
        return $this->setCeid((integer) $ceid);
    }

    /**
     * Set primary-key on orderId
     *
     * @param $oeid
     * @return $this
     */
    public function setOeid($oeid)
    {
        return $this->addParam('oeid', (integer) $oeid);
    }

    /**
     * Set primary-key on orderId
     *
     * @param $oeid
     * @return $this
     */
    public function setOrderId($oeid)
    {
        return $this->setOeid((integer)$oeid);
    }

    /**
     * Set primary-key on orderNumber
     *
     * @param $ordnr
     * @return $this
     */
    public function setOrdnr($ordnr)
    {
        return $this->addParam('ordnr', (string) $ordnr);
    }

    /**
     * Set primary-key on orderNumber
     *
     * @param $ordnr
     * @return $this
     */
    public function setOrderNumber($ordnr)
    {
        return $this->setOrdnr((integer) $ordnr);
    }

    /**
     * Set primary-key on not released orders
     *
     * @param $unfree
     * @return $this
     */
    public function setUnfree($unfree)
    {
        return $this->addParam('return_unfree', (bool) $unfree);
    }

    /**
     * Set primary-key onid of agreement
     *
     * @param $scale
     * @return $this
     */
    public function setScale($scale)
    {
        return $this->addParam('scale', (integer) $scale);
    }

    /**
     * Set primary-key on not released orders
     *
     * @param $view
     * @return $this
     */
    public function setView($view)
    {
        return $this->addParam('view', (string) $view);
    }


    /**
     * Return all bills
     *
     * @param $bool
     * @return $this
     */
    public function addReturnAccountEntries($bool = true)
    {
        return $this->addParam('return_account_entrys', (bool) $bool);
    }

    /**
     * Return all bookings
     *
     * @param $bool
     * @return $this
     */
    public function addReturnSettlements($bool = true)
    {
        $this->addReturnAccountEntries();
        return $this->addParam('return_settlements', (bool) $bool);
    }


    /**
     * Return all dispositions
     *
     * @param $bool
     * @return $this
     */
    public function addReturnDispositions($bool = true)
    {
        return $this->addParam('return_dispositions', (bool) $bool);
    }

    /**
     * Return all active dispositions
     *
     * @param $bool
     * @return $this
     */
    public function addReturnActiveDispositions($bool = true)
    {
        $this->addReturnDispositions();
        return $this->addParam('return_active', (bool) $bool);
    }

    /**
     * Return all disposition prices
     *
     * @param $bool
     * @return $this
     */
    public function addReturnDispositionPrices($bool = true)
    {
        $this->addReturnDispositions();
        return $this->addParam('return_disposition_prices', (bool) $bool);
    }

    /**
     * Return all disposition messages
     *
     * @param $bool
     * @return $this
     */
    public function addReturnDispositionMessages($bool = true)
    {
        $this->addReturnDispositions();
        return $this->addParam('return_msg', (bool) $bool);
    }


    /**
     * Return all domains
     *
     * @param $bool
     * @return $this
     */
    public function addReturnDomain($bool = true)
    {
        return $this->addParam('return_domain', (bool) $bool);
    }

    /**
     * Return all domainQuotas
     *
     * @param $bool
     * @return $this
     */
    public function addReturnDomainQuotas($bool = true)
    {
        return $this->addParam('return_domcon', (bool) $bool);
    }

    /**
     * Return customer-data
     *
     * @param $bool
     * @return $this
     */
    public function addReturnCustomer($bool = true)
    {
        return $this->addParam('return_customer', (bool) $bool);
    }

    /**
     * Return customer-address
     *
     * @param $bool
     * @return $this
     */
    public function addReturnCustomerAddress($bool = true)
    {
        $this->addReturnCustomer();
        return $this->addParam('return_adress', (bool) $bool);
    }

    /**
     * Return customer-overview
     *
     * @param $bool
     * @return $this
     */
    public function addReturnCustomerOverview($bool = true)
    {
        $this->addReturnCustomer();
        return $this->addParam('return_customer_overview', (bool) $bool);
    }

    /**
     * Return limits
     *
     * @param $bool
     * @return $this
     */
    public function addReturnLimits($bool = true)
    {
        return $this->addParam('return_limits', (bool) $bool);
    }

    /**
     * Return only limit limits
     *
     * @param $bool
     * @return $this
     */
    public function addReturnLimitLimits($bool = true)
    {
        $this->addReturnLimits();
        return $this->addParam('return_max_only', (bool) $bool);
    }

    /**
     * Return load
     *
     * @param $bool
     * @return $this
     */
    public function addReturnLoad($bool = true)
    {
        return $this->addParam('return_load', (bool) $bool);
    }

    /**
     * Return termination status
     *
     * @param $bool
     * @return $this
     */
    public function addReturnTerminationStatus($bool = true)
    {
        return $this->addParam('return_is_canceled', (bool) $bool);
    }

    /**
     * Return treaty group id
     *
     * @param $bool
     * @return $this
     */
    public function addReturnScale($bool = true)
    {
        return $this->addParam('return_scale', (bool) $bool);
    }

    /**
     * Return tariff
     *
     * @param $bool
     * @return $this
     */
    public function addReturnTariff($bool = true)
    {
        return $this->addParam('return_tariff', (bool) $bool);
    }
}