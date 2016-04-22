<?php

namespace rpf\api\module;
use rpf\api\apiModule;
use rpf\system\module\exception;
use rpf\system\module\log;

require_once __DIR__.'/../bb.rpc.class.php';

/**
 * Implementation of bbRpc
 * @link https://doku.premium-admin.eu/doku.php/api/methoden/bbrpc/start
 */
class bbRpc_call extends apiModule
{
    /**
     * @var array $cache[$requestString] = $result
     */
    protected $cache = [];

    /**
     * Implementation of bbRpc::call with caching and logging
     *
     * @param string $sMethod
     * @param array $hArgs
     * @param bool $cache
     * @return mixed
     * @throws exception
     */
    public function call($sMethod,$hArgs=array(),$cache = true)
    {
        // Create readable request string for logging & caching
        $requestString = '';
        foreach ($hArgs as $name => $value)
        {
            $requestString .= empty($requestString) ? $sMethod.'(' : ',';
            $requestString .= "'$name' => '$value'";
        }
        $requestString .= ')';


        if ($cache === true && isset($this->cache[$requestString]))
        {
            log::debug('Getting RPC-Response from runtime-cache', __METHOD__ . "($requestString)");
        }
        else if ($cache === 'memcache')
        {
            throw new exception('Sorry, memcach is not implemented yet');
        }
        else
        {
            $duration = microtime(1);
            global $_BBRPC_Msgs;
            $this->cache[$requestString] = bbRpc::call($sMethod,$hArgs);
            $duration = round(microtime(1)-$duration, 3);
            $resultCounter = count($this->cache[$requestString]);
            log::debug("Performing RPC-Request within $duration sec.", __METHOD__);
            log::debug("Getting $resultCounter rows ", $requestString);
            $this->fetchRpcLog();
        }
        return $this->cache[$requestString];
    }
}