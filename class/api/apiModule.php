<?php

namespace rpf\api;
use rpf\apiResponse\apiResponse;
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
     * @var array rpc response
     */
    protected $rpcResponse = [];


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
     * Get result of api-request as array
     *
     * @param bool|string $cache (false, true = runtime, memcache)
     * @param string $primaryKey Use different field as primary key
     * @return array|bool
     * @throws module\exception
     */
    public function getArray($cache = true, $primaryKey = 'default')
    {
        $result = array();
        $this->rpcResponse = $this->getApi()->getRpcCall()->call($this->rpcMethod, $this->rpcParams, $cache);

        if (!is_array($this->rpcResponse))
        {
            throw new module\exception("Sorry, could not fetch any row");
        }

        if ($primaryKey == 'default')
        {
            return $this->rpcResponse;
        }
        else
        {
            foreach ($this->rpcResponse as $row)
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


    public function getObject($primaryKey, $primaryKeyField = 'default', $cache = true)
    {
        $class = '\rpf\apiResponse\module\\'.str_replace('::', '_', $this->rpcMethod);
        $response = $this->getArray($cache, $primaryKeyField);
        return $this->getApiResponse()->initialize($class, $response)->get($primaryKey);
    }

    public function getResource($primaryKeyField = 'default', $cache = true)
    {
        $class = '\rpf\apiResponse\module\\'.str_replace('::', '_', $this->rpcMethod);
        $response = empty($this->rpcResponse) ? $this->getArray($cache, $primaryKeyField) : $this->rpcResponse;
        return $this->getApiResponse()->initialize($class, $response)->getResource();
    }
}