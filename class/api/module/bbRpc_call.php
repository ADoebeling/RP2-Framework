<?php

namespace rpf\api\module;
use rpf\api\apiModule;
use rpf\system\module\exception;
use rpf\system\module\log;

require_once __DIR__ . '/../bb.rpc.php';

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
     * @param null $sMethod
     * @param array $hArgs
     * @param bool $cache
     * @param bool $forceAuth
     * @return bool|mixed
     * @throws \Exception
     * @throws exception
     *
     * @todo ForceAuth
     */
    public function call($sMethod = NULL,$hArgs=array(),$cache = true, $forceAuth = true)
    {

        $sMethod = $sMethod == NULL ? $this->rpcMethod : $sMethod;
        $hArgs = empty($hArgs) ? $this->rpcParams : $hArgs;


        if ($this->getApi()->getRpcAuth()->auth()) //        if ($forceAuth && $this->getApi()->getRpcAuth()->auth() || !$forceAuth)

        {
            // Create readable request string for logging & caching
            $requestString = "$sMethod(";
            foreach ($hArgs as $name => $value) {
                $requestString .= empty($requestString) ? '' : ',';
                $requestString .= "'$name' => '$value'";
            }
            $requestString .= ')';

            if ($cache === true && isset($this->cache[$requestString])) {
                //log::debug('Getting RPC-Response from runtime-cache', __METHOD__ . "($requestString)");
            } else if ($cache === 'memcache') {
                throw new exception('Sorry, memcach is not implemented yet');
            } else {
                log::setStopwatch();
                $this->cache[$requestString] = \bbRpc::call($sMethod, $hArgs);
                $resultCounter = count($this->cache[$requestString]);
                log::debug("Getting $resultCounter rows", $requestString);
                $this->getApi()->getRpcMessages()->getMessages();
            }
            return $this->cache[$requestString];
        }
        return false;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    protected function addParam($name, $value)
    {
        log::debug('', __METHOD__."($name, $value)");
        $this->rpcParams[$name] = $value;
        return $this;
    }
}