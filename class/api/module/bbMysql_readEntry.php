<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbMysql::readEntry
 * (No public api-documentation available)
 *
 * @package system\module
 */
class bbMysql_readEntry extends apiModule
{
    protected $rpcMethod = 'bbMysql::readEntry';


    /**
     * Set filter on name
     *
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->addParam('name', (string) $name);
    }


    /**
     * Set filter on oeid
     * (hidden background-order-id, not order-nr)
     *
     * @param int $oeid
     * @return $this
     */
    public function setOeid($oeid)
    {
        return $this->addParam('oeid', (integer) $oeid);
    }


    /**
     * Set filter on seid
     *
     * @param ???
     * @return $this
     */
    public function setDomainId($seid)
    {
        return $this->addParam('seid', (integer) $seid);
    }
}