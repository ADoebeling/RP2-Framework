<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbMysql::makeBackup
 * (No public api-documentation available)
 *
 * @package system\module
 */
class bbMysql_makeBackup extends apiModule
{
    protected $rpcMethod = 'bbMysql::makeBackup';

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