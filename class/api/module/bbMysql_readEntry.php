<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbMysql::readEntry
 * (No public api-documentation available)
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
     * @param $seid
     * @return ??
     */
    public function setSeid($seid)
    {
        return $this->addParam('seid', (integer) $seid);
    }
}