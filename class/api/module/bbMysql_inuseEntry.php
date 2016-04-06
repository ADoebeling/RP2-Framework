<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbMysql::inuseEntry
 * (No public api-documentation available)
 *
 * @package system\module
 */
class bbMysql_inuseEntry extends apiModule
{
    protected $rpcMethod = 'bbMysql::inuseEntry';

    /**
     * Set filter on seid
     *
     * @param $seid
     * @return $this
     */
    public function setName($seid)
    {
        return $this->addParam('name', (integer) $seid);
    }

}