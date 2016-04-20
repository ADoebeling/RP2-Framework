<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbDomain::readHandles
 *
 * @package system\module
 */
class bbDomain_readHandles extends apiModule
{
    protected $rpcMethod = 'bbDomain::readHandles';

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
     * Set filter on ServiceObject Entry Id
     *
     * @param $seid
     * @return $this
     */
    public function setSeid($seid)
    {
        return $this->addParam('seid',(integer) $seid);
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
     * Set filter on dn
     *
     * @param $dn
     * @return $this
     */
    public function setDn($dn)
    {
        return $this->addParam('dn', $dn);
    }

    /**
     * Set filter on ??
     *
     * @param $pseid
     * @return $this
     */
    public function setPSeid($pseid)
    {
        return $this->addParam('p_seid', (integer) $pseid);
    }

    /**
     * Return Spf
     *
     * @param $bool
     * @return $this
     */
    public function addReturnSpf($bool = true)
    {
        return $this->addParam('return_spf', (bool) $bool);
    }
}