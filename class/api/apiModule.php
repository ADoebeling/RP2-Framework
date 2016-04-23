<?php

namespace rpf\api;
use rpf\system\module;

defined('BBRPC_URL') or define('BBRPC_URL', RPF_API_MODULE_BBRPC_SETURL_URL);
defined('BBRPC_COOKIE') or define('BBRPC_COOKIE', RPF_API_MODULE_AUTH_COOKIE);

require_once __DIR__.'/bb.rpc.php';

/**
 * RPF API-Module-Class
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link https://xing-ad.1601.com
 * @link https://www.1601.com
 */
class apiModule extends module
{
    /**
     * @var bool|int
     */
    protected $rpcUserId = false;

    /**
     * @var bool|string
     */
    protected $rpcUrl = false;

    /**
     * Name of the RPC-Method
     * @var string
     */
    protected $rpcMethod = 'UNDEFINED';

    /**
     * @var array call-params (filter, return, format, ...)
     */
    protected $rpcParams = array();

    /**
     * Add param for bbRpc::call
     * (Alias for $this->getApi()->getRpcCall()->addParam($name, $value) )
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    protected function addParam($name, $value)
    {
        $this->getApi()->getRpcCall()->addParam($name, $value);
        return $this;
    }

    /**
     * Get result of api-request
     *
     * @param bool|string $cache (false, true = runtime, memcache)
     * @param string $primaryKey Use different field as primary key
     * @return array|bool
     * @throws module\exception
     */
    public function get($cache = true, $primaryKey = 'default')
    {
        $result = array();
        $apiResult = $this->getApi()->getRpcCall()->call($this->rpcMethod, $this->rpcParams, $cache);

        if (!is_array($apiResult))
        {
            throw new module\exception("Sorry, could not fetch any row");
        }

        if ($primaryKey == 'default')
        {
            return $apiResult;
        }
        else
        {
            foreach ($apiResult as $row)
            {
                // isset return false if element is NULL
                if (!isset($row[$primaryKey]) && @$row[$primaryKey] !== NULL)
                {
                    throw new module\exception("Sorry, primary-key '$primaryKey' not found in api-result: \n".print_r($row, 1));
                }
                else if (isset($result[$row[$primaryKey]]))
                {
                    throw new module\exception("Sorry, duplicate entry on primary-key '$primaryKey'");
                }
                else
                {
                    $result[$row[$primaryKey]] = $row;
                }
            }
            return $result;
        }
    }
}