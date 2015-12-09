<?php namespace www1601com\df_rp;

use www1601com\df_rp\module;

//require_once('http://share.bfdev.at/5.3/bb.rpc.class.php');

require_once __DIR__.'/bb.rpc.php';
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
     * @throws \Exception
     */
    public function auth($rp2InstanceUrl, $rp2ApiUser, $rp2ApiPwd)
    {
        $this->rpc->setUrl($rp2InstanceUrl);
        $userId = $this->rpc->auth($rp2ApiUser, $rp2ApiPwd);
        if (!$userId)
        {
            throw new \Exception("Login as $rp2ApiUser failed", 401);
        }
        // https://doku.premium-admin.eu/doku.php/api/methoden/bbrpc/setutf8native
        $this->rpc->setUTF8Native(true);
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     * @TODO Error Handler
     */
    public function httpAuth()
    {
        // Get the correct path on http AND ssh/bash
        $path = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PWD'];
        preg_match("/\\/.*\\/(\\d*)_\\d*\\/.*/", $path, $matches);
        $dfOrderNr = $matches[1];

        $rp2InstanceUrl = "http://$dfOrderNr.premium-admin.eu/";



        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            static::sendHttpAuth($dfOrderNr);
        }

        $this->auth($rp2InstanceUrl, $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

        return $this;
    }

    protected static function sendHttpAuth($dfOrderNr)
    {
        header("WWW-Authenticate: Basic realm=\"Please enter your RP2-User and Password for A$dfOrderNr\"");
        header('HTTP/1.0 401 Unauthorized');
        echo "Please authenticate with your RP2-Username and -Password.\n".$_SERVER['PHP_AUTH_USER'];
            exit;
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
        #echo " ######## RPC-Call: $sMethod: ";
        $result = $this->rpc->call($sMethod,$hArgs,$hPlaceholders);
        $duration = round(microtime(1)-$startTime, 3);
        #echo "$duration Sek. ########\n\n";
        return $result;
    }

}