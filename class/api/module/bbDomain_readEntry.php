<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbDomain::readEntry
 * (No public api-documentation available)
 *
 * @package system\module
 */
class bbDomain_readEntry extends apiModule
{
    protected $rpcMethod = 'bbDomain::readEntry';

    /**
     * Set filter on domain-id
     *
     * @param int $dn
     * @return $this
     */
    public function setDomainId($dn)
    {
        return $this->addParam('dn', (integer) $dn);
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
     * (???)
     *
     * @param $seid
     * @return $this
     * @todo documentation
     */
    public function setSeid($seid)
    {
        return $this->addParam('seid', (integer) $seid);
    }

    /**
     * Add Subdomain-infos to result
     *
     * @param bool $bool
     * @return $this
     */
    public function addSubdomain($bool = true)
    {
        return $this->addParam('return_subdomain', (bool) $bool);
    }

    /**
     * Add Frontpage-Extensions to result
     * (has anybody ever used this feature?! Send me an email :D )
     *
     * @param bool $bool
     * @return $this
     */
    public function addFrontpage($bool = true)
    {
        return $this->addParam('return_frontpage', (bool) $bool);
    }

    /**
     * @param bool $bool
     * @return $this
     * @todo documentation
     */
    public function addMajor($bool = true)
    {
        return $this->addParam('return_major', (bool) $bool);
    }

    /**
     * Add domain-settings to result
     * (e. g. php-version)
     *
     * @param bool $bool
     * @return $this
     */
    public function addSettings($bool = true)
    {
        return $this->addParam('return_settings', (bool) $bool);
    }

    /**
     * Add domain-handles to result
     * (owner, admin-c, tech-c, zone-c)
     *
     * @param bool $bool
     * @return $this
     */
    public function addHandles($bool = true)
    {
        return $this->addParam('return_handles', (bool) $bool);
    }

    /**
     * @param bool $bool
     * @return $this
     * @todo documentation
     */
    public function addStaid($bool = true)
    {
        return $this->addParam('return_staid', (bool) $bool);
    }

    /**
     * Add dns-records to result
     *
     * @param bool $bool
     * @return $this
     */
    public function addNameserver($bool = true)
    {
        return $this->addParam('return_nameserver', (bool) $bool);
    }

    /**
     * Add spf-dns-records to result
     * (???)
     *
     * @param bool $bool
     * @return $this
     * @todo documentation
     */
    public function addSpf($bool = true)
    {
        return $this->addParam('return_spf', (bool) $bool);
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function addWebalizerSettings($bool = true)
    {
        return $this->addParam('return_webalizersettings', (bool) $bool);
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function addPhpini($bool = true)
    {
        return $this->addParam('return_phpini', (bool) $bool);
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function addResellerFields($bool = true)
    {
        return $this->addParam('return_reseller_fields', (bool) $bool);
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function addRpc($bool = true)
    {
        return $this->addParam('return_rpc', (bool) $bool);
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function addLimits($bool = true)
    {
        return $this->addParam('return_limits', (bool) $bool);
    }

    /**
     * @param bool $cache
     * @param string $primaryKey
     * @return array|bool
     * @throws \rpf\system\module\exception
     */
    public function getArray($cache = true, $primaryKey = 'name')
    {
        return parent::getArray($cache, $primaryKey);
    }

    /**
     * @param bool $cache
     * @param string $primaryKey
     * @return \rpf\apiResponse\apiResponse
     */
    public function getAll($cache = true, $primaryKey = 'name')
    {
        return parent::getAll($cache, $primaryKey);
    }


    /**
     * @param $primaryKey
     * @param string $primaryKeyField
     * @param bool $cache
     * @return bbDomainReadEntry
     */
    public function getObject($primaryKey, $primaryKeyField = 'name', $cache = true)
    {
        return parent::getObject($primaryKey, $primaryKeyField, $cache);
    }


    /**
     * @param string $primaryKeyField
     * @param bool $cache
     * @return \rpf\apiResponse\module\bbDomain_readEntry
     */
    public function getResource($primaryKeyField = 'name', $cache = true)
    {
        return parent::getResource($primaryKeyField, $cache);
    }
}