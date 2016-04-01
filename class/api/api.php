<?php

namespace www1601com\df_rp\api;
use www1601com\df_rp\api\module\bbDomain_readEntry;
use www1601com\df_rp\api\module\bbOrder_readDisposition;
use www1601com\df_rp\api\module\customer;
use www1601com\df_rp\api\module\email;
use www1601com\df_rp\api\module\order;
use www1601com\df_rp\api\module\user;
use www1601com\df_rp\module;
use www1601com\df_rp\system;


/**
 * Wrapper-Class for all interactions with the rp2-api
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/df_rp2
 * @link https://github.com/ADoebeling
 * @link https://xing-ad.1601.com
 * @package www1601com\df_rp
 *
 * @version 0.1.151002_dev_1ad
 */
class api extends system {

    /**
     * @var object bbRpc
     */
    public $rpc;

    /**
     * @var object module\user
     */
    public $user;

    /**
     * @var object module\customer
     */
    public $customers;

    /**
     * @var object module/order
     */
    public $orders;

    /**
     * @var object module/bbOrder_readDisposition
     */
    public $bbOrder_readDisposition;

    /**
     * @var object module\email
     */
    public $email;


    /**
     * Building instances for all sub-objects
     */
    public function __construct()
    {
        parent::__construct();

        // V2-Implementation
        $this->rpc = new bbRpc();
        $this->user = new user($this);
        $this->bbDomain_readEntry = new bbDomain_readEntry($this);
        $this->bbOrder_readDisposition = new bbOrder_readDisposition($this);

        // Deprecated V1-Implementation
        $this->customers = new customer($this);
        $this->orders = new order($this);
        $this->emails = new email($this);
    }


    /**
     * DEPRECATED
     * Alias for user::auth()
     *
     * @param $rp2InstanceUrl
     * @param $rp2ApiUser
     * @param $rp2ApiPwd
     * @return $this
     */
    public function auth($rp2InstanceUrl, $rp2ApiUser, $rp2ApiPwd)
    {
        $this->user->auth($rp2InstanceUrl, $rp2ApiUser, $rp2ApiPwd);
        return $this;
    }

    /**
     * DEPRECATED
     * Alias for user::httpAuth()
     *
     * @return $this
     */
    public function httpAuth()
    {
        $this->user->httpAuth();
        return $this;
    }


    /**
     * Wrapper for all api-calls
     *
     * @param $sMethod
     * @param array $hArgs
     * @param null $hPlaceholders
     * @return null
     * @throws \Exception
     */
    public function call($sMethod,$hArgs=array(),$hPlaceholders=null)
    {
        $startTime = microtime(1);
        $result = $this->rpc->call($sMethod,$hArgs,$hPlaceholders);
        $duration = round(microtime(1)-$startTime, 3);

        // Parse Args for debugging
        $log = "API-Call: call('$sMethod', [";
        foreach ($hArgs as $name => $value)
        {
            if (is_array($value))
            {
                throw new \Exception("Sorry, Params can't be multidimensional");
            }
            $log .= "'$name' => '$value',";
        }
        $log .= "]); // Results: ".count($result)." // ExecutionTime: $duration";

        $this->log->debug($log);
        return $result;
    }

}

