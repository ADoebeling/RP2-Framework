<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbEmail::readAccount
 *
 * @package system\module
 */
class bbEmail_readAccount extends apiModule
{
    protected $rpcMethod = 'bbEmail::readAccount';

    /**
     * Set filter on email
     *
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        return $this->addParam('email', (string) $email);
    }

    /**
     * Set filter on oeid
     *
     * @param $oeid
     * @return $this
     */
    public function setOeid($oeid)
    {
        return $this->addParam('oeid', (integer) $oeid);
    }

    /**
     * Set filter on seid
     *
     * @param $seid
     * @return $this
     */
    public function setSeid($seid)
    {
        return $this->addParam('seid', (integer) $seid);
    }

    /**
     * Set filter on ServiceObject Entry Id
     *
     * @param $seid
     * @return $this
     */
    public function setServiceObjectEntryId($seid)
    {
        return $this->setSeid($seid);
    }

    /**
     * Set filter on order-id
     *
     * @param $oeid
     * @return $this
     */
    public function setOrderId($oeid)
    {
        return $this->setOeid($oeid);
    }

    /**
     * Set filter on p_seid
     *
     * @param $pseid
     * @return $this
     */
    public function setPSeid($pseid)
    {
        return $this->addParam('p_seid', (integer) $pseid);
    }


    /**
     * return Rpc
     *
     * @param $bool
     * @return $this
     */
    public function addRpc($bool = true)
    {
        return $this->addParam('return_rpc', $bool);
    }

    /**
     * return Used
     *
     * @param $bool
     * @return $this
     */
    public function addUsed($bool = true)
    {
        return $this->addParam('return_used', $bool);
    }

    /**
     * return Sievefilter-Overview
     *
     * @param $bool
     * @return $this
     */
    public function addSievefilterOverview($bool = true)
    {
        return $this->addParam('return_sievefilter_overview', $bool);
    }

    /**
     * return Stat
     *
     * @param $bool
     * @return $this
     */
    public function addStat($bool = true)
    {
        return $this->addParam('return_stat', $bool);
    }

    /**
     * return Time
     *
     * @param $bool
     * @return $this
     */
    public function addArTime($bool = true)
    {
        return $this->addParam('return_ar_time', $bool);
    }
}