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
    public function setSeid($seid)
    {
        return $this->addParam('seid', $seid);
    }

}