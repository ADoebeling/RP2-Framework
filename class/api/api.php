<?php

namespace rpf\api;
use rpf\api\module\bbDomain_readEntry;
use rpf\api\module\bbCustomer_readEntry;
use rpf\api\module\bbCustomer_readAddress;
use rpf\api\module\bbCustomer_readDiscount;
use rpf\api\module\bbCustomer_readPayment;
use rpf\api\module\bbCustomer_readTitle;
use rpf\api\module\bbCustomer_validateAddress;
use rpf\api\module\bbCustomer_validateEntry;
use rpf\api\module\bbCustomer_validatePayinfo;
use rpf\api\module\bbOrder_readDisposition;
use rpf\api\module\customer;
use rpf\api\module\email;
use rpf\api\module\order;
use rpf\api\module\placeholder;
use rpf\api\module\user;
use rpf\system\module\log;

/**
 * RPF API-Class
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link https://xing-ad.1601.com
 * @link https://www.1601.com
 */
class api extends apiModule
{
    /**
     * @return user
     */
    public function getUser()
    {
        return $this->getModule(user::class);
    }

    /**
     * @return bbDomain_readEntry
     */
    public function getDomainReadEntry()
    {
        return $this->getModule(bbDomain_readEntry::class);
    }

    /**
     * @return bbCustomer_readEntry
     */
    public function getCustomerReadEntry()
    {
        return $this->getModule(bbCustomer_readEntry::class);
    }

    /**
     * @return bbCustomer_readAddress
     */
    public function getCustomerReadAddress()
    {
        return $this->getModule(bbCustomer_readAddress::class);
    }

    /**
     * @return bbCustomer_readDiscount
     */
    public function getCustomerReadDiscount()
    {
        return $this->getModule(bbCustomer_readDiscount::class);
    }

    /**
     * @return bbCustomer_readPayment
     */
    public function getCustomerReadPayment()
    {
        return $this->getModule(bbCustomer_readPayment::class);
    }

    /**
     * @return bbCustomer_readTitle
     */
    public function getCustomerReadTitle()
    {
        return $this->getModule(bbCustomer_readTitle::class);
    }

    /**
     * @return bbCustomer_validateAddress
     */
    public function getCustomerValidateAddress()
    {
        return $this->getModule(bbCustomer_validateAddress::class);
    }

    /**
     * @return bbCustomer_validateEntry
     */
    public function getCustomerValidateEntry()
    {
        return $this->getModule(bbCustomer_validateEntry::class);
    }

    /**
     * @return bbCustomer_validateEntry
     */
    public function getCustomerValidatePayinfo()
    {
        return $this->getModule(bbCustomer_validatePayinfo::class);
    }

    /**
     * @return bbOrder_readDisposition
     */
    public function getOrderReadDisposition()
    {
        return $this->getModule(bbOrder_readDisposition::class);
    }


    /**
     * @return placeholder
     */
    public function getPlaceholder()
    {
        return $this->getModule(placeholder::class);
    }


    /**
     * DEPRECATED - Don't use any more
     * @return customer
     */
    public function getCustomer()
    {
        log::debug("Usage of deprecated object 'customer'", __METHOD__);
        return $this->getModule(customer::class);
    }


    /**
     * DEPRECATED - Don't use any more
     * @return email
     */
    public function getEmail()
    {
        log::debug("Usage of deprecated object 'email'", __METHOD__);
        return $this->getModule(email::class);
    }

    /**
     * DEPRECATED - Don't use any more
     * @return order
     */
    public function getOrder()
    {
        log::debug("Usage of deprecated object 'order'", __METHOD__);
        return $this->getModule(order::class);
    }


}

