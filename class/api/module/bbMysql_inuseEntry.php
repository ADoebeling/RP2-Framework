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
    public function setSeid($seid)
    {
        return $this->addParam('seid', $seid);
    }
}