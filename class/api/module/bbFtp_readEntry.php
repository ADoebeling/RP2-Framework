<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbFtp::readEntry
 *
 * @package system\module
 */
class bbFtp_readEntry extends apiModule
{
    protected $rpcMethod = 'bbFtp::readEntry';

    /**
     * Set filter on username
     *
     * @param $username
     * @return $this
     */
    public function setUsername($username)
    {
        return $this->addParam('username', (string) $username);
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
}