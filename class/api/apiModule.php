<?php

namespace rpf\api;
use rpf\system\module;

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
     * Name of the RPC-Methode
     * @var string
     */
    protected $rpcMethod = 'UNDEFINED';

    /**
     * @var array call-params (filter, return, format, ...)
     */
    private $rpcParams = array();

    /**
     * @var array ['requestString' => $result]
     */
    private $rpcCache = array();


      /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    protected function addParam($name, $value)
    {
        $this->rpcParams[(string) $name] = $value;
        return $this;
    }

    /**
     * Get result of api-request
     *
     * @param bool|string $cache (false, true = runtime, memcache)
     * @return array|bool
     */
    public function get($cache = true)
    {
        return $this->getRpcResponse($this->rpcMethod, $this->rpcParams, null, $cache);
    }

    /**
     * Wrapper for all api-calls with build-in caching
     *
     * @param $sMethod
     * @param array $hArgs
     * @param null $hPlaceholders
     * @param bool|string $cache
     * @return null
     * @throws \Exception
     */
    private function getRpcResponse($sMethod, $hArgs=array(), $hPlaceholders=null, $cache = true)
    {
        $requestString = '';
        foreach ($this->rpcParams as $name => $value)
        {
            if (!is_string($value) && !is_bool($value))
            {
                throw new module\exception("Sorry, Params can't be ".\gettype($value));
            }
            $requestString .= empty($requestString) ? $sMethod.'(' : ',';
            $requestString .= "'$name' => '$value'";
        }
        $requestString .= ')';



        if ($cache === true && isset($this->rpcCache[$requestString]))
        {
            module\log::debug('Getting RPC-Response from runtime-cache', __METHOD__ . "($requestString)");
            return $this->rpcCache[$requestString];
        }
        else if ($cache === 'memcache')
        {
            throw new module\exception('Sorry, memcach is not implemented yet');
        }
        else
        {
            $duration = microtime(1);
            global $_BBRPC_Msgs;
            $this->rpcCache[$requestString] = bbRpc::call($sMethod,$hArgs,$hPlaceholders);
            $duration = round(microtime(1)-$duration, 3);
            $resultCounter = count($this->rpcCache[$requestString]);
            module\log::debug("Performing RPC-Request within $duration sec.", __METHOD__);
            module\log::debug("Getting $resultCounter rows ", $requestString);
            $this->fetchRpcLog();
        }
        return $this->rpcCache[$requestString];
    }

    /**
     * @todo fix me, seems like I'm broken
     */
    protected function fetchRpcLog()
    {
        // I realy don't like the way of fetching RPC-Error-Messages...
        //$hLoglvl = array("error"=>0, "warn"=>1, "notice"=>2, "ok"=>3, "debug"=>4);
        global $_BBRPC_Msgs;

        if (is_array($_BBRPC_Msgs))
        {
            foreach ($_BBRPC_Msgs as $key => &$hMsg)
            {
                switch ($hMsg["typ"])
                {
                    case 0:
                        module\log::error("RPC-Msg.: $hMsg", __METHOD__);
                        break;
                    case 1:
                        module\log::warning("RPC-Msg.: $hMsg", __METHOD__);
                        break;
                    case 2:
                        module\log::info("RPC-Msg.: $hMsg", __METHOD__);
                        break;
                    case 3:
                        module\log::debug("RPC-Msg.: OK! $hMsg", __METHOD__);
                        break;
                    case 4:
                        module\log::debug("RPC-Msg.: $hMsg", __METHOD__);
                        break;
                }
                unset($_BBRPC_Msgs[$key]);
            }
        }
    }
}