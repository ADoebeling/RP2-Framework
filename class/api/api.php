<?php namespace www1601com\df_rp;

use www1601com\df_rp\module;

require_once('http://share.bfdev.at/5.3/bb.rpc.class.php');
require_once __DIR__.'/module/customer.php';
require_once __DIR__.'/module/order.php';
require_once __DIR__.'/module/email.php';

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
class api {

    /**
     * @var object bbRpc
     */
    protected $rpc;

    /**
     * @var object module\customer
     */
    public $customers;

    /**
     * @var object module/order
     */
    public $orders;

    /**
     * @var object module\email
     */
    public $email;


    /**
     * Building instances for all sub-objects
     */
    public function __construct()
    {
        $this->rpc = new \bbRpc();
        $this->customers = new module\customer($this);
        $this->orders = new module\order($this);
        $this->emails = new module\email($this);
    }

    /**
     * Authorizes the given user at the api
     *
     * @param $rp2InstanceUrl
     * @param $rp2ApiUser
     * @param $rp2ApiPwd
     * @return $this
     */
    public function auth($rp2InstanceUrl, $rp2ApiUser, $rp2ApiPwd)
    {
        // TODO: Error handler
        $this->rpc->setUrl($rp2InstanceUrl);
        $this->rpc->auth($rp2ApiUser, $rp2ApiPwd);
        return $this;
    }

    /**
     * Wrapper for all api-calls
     *
     * @param $sMethod
     * @param array $hArgs
     * @param null $hPlaceholders
     * @return array
     */
    public function call($sMethod,$hArgs=array(),$hPlaceholders=null)
    {
        $startTime = microtime(1);
        //echo " ######## RPC-Call: $sMethod: ";
        $result = $this->rpc->call($sMethod,$hArgs,$hPlaceholders);
        $duration = round(microtime(1)-$startTime, 3);
        //echo "$duration Sek. ########\n\n";
        return $result;
    }

}