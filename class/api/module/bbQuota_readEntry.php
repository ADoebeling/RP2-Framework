<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbQuota::readEntry
 *
 * @package system\module
 */
class bbQuota_readEntry extends apiModule
{
    protected $rpcMethod = 'bbQuota::readEntry';

    /**
     * Set filter on pfad
     *
     * @param $pfad
     * @return $this
     */
    public function setPfad($pfad)
    {
        return $this->addParam('pfad',(string) $pfad);
    }

    /**
     * Set filter on oeid
     *
     * @param $oeid
     * @return $this
     */
    public function setOeid($oeid)
    {
        return $this->addParam('oeid',(integer) $oeid);
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
     * Set filter on uid
     *
     * @param $uid
     * @return $this
     */
    public function setUid($uid)
    {
        return $this->addParam('uid', (integer) $uid);
    }

    /**
     * Return ssh-account
     *
     * @param bool $bool
     * @return $this
     */
    public function addReturnSsh($bool = true)
    {
        return $this->addParam('return_ssh', (bool) $bool);
    }

    /**
     * Return the base
     *
     * @param bool $bool
     * @return $this
     */
    public function addReturnBase($bool = true)
    {
        return $this->addParam('return_base', (bool) $bool);
    }
}