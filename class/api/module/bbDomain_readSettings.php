<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbDomain::readSettings
 *
 * @package system\module
 */
class bbDomain_readSettings extends apiModule
{
    protected $rpcMethod = 'bbDomain::readSettings';

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
}