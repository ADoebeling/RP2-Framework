<?php

namespace www1601com\df_rp\api\module;
use www1601com\df_rp\api\apiModule;

/**
 * Implementation of bbDomain
 * Public documentation is missing :(
 * @package www1601com\df_rp\module
 */
class bbDomain_readEntry extends apiModule
{
    public function __construct($system)
    {
        $this->rpcClass = 'bbDomain::readEntry';
        parent::__construct($system);
    }

    public function setDomainId($dn)
    {
        return $this->addParam('dn', (integer) $dn);
    }

    public function setOeid($oeid)
    {
        return $this->addParam('oeid', (integer) $oeid);
    }

    public function setSeid($seid)
    {
        return $this->addParam('seid', (integer) $seid);
    }

    public function addSubdomain($bool = true)
    {
        return $this->addParam('return_subdomain', (bool) $bool);
    }

    public function addFrontpage($bool = true)
    {
        return $this->addParam('return_frontpage', (bool) $bool);
    }

    public function addMajor($bool = true)
    {
        return $this->addParam('return_major', (bool) $bool);
    }

    public function addSettings($bool = true)
    {
        return $this->addParam('return_settings', (bool) $bool);
    }

    public function addHandles($bool = true)
    {
        return $this->addParam('return_handles', (bool) $bool);
    }

    public function addStaid($bool = true)
    {
        return $this->addParam('return_staid', (bool) $bool);
    }

    public function addNameserver($bool = true)
    {
        return $this->addParam('return_nameserver', (bool) $bool);
    }

    public function addSpf($bool = true)
    {
        return $this->addParam('return_spf', (bool) $bool);
    }

    public function addWebalizerSettings($bool = true)
    {
        return $this->addParam('return_webalizersettings', (bool) $bool);
    }

    public function addPhpini($bool = true)
    {
        return $this->addParam('return_phpini', (bool) $bool);
    }

    public function addResellerFields($bool = true)
    {
        return $this->addParam('return_reseller_fields', (bool) $bool);
    }

    public function addRpc($bool = true)
    {
        return $this->addParam('return_rpc', (bool) $bool);
    }

    public function addLimits($bool = true)
    {
        return $this->addParam('return_limits', (bool) $bool);
    }
}