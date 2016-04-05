<?php

namespace rpf\api;
use rpf\api\module\bbDomain_readEntry;
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

