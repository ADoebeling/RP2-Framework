<?php

namespace rpf\apiResponse;
use rpf\system\module;
use rpf\system\module\exception;

/**
 * RPF api response module
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link https://xing-ad.1601.com
 * @link https://www.1601.com
 */
class apiResponseModule
{
    protected $rpcResponse = [];

    public function __construct(array $rpcResponse)
    {
        if (!is_array($rpcResponse)) throw new exception("array expected");
        /*foreach ($apiResponse as $key => $value)
        {
            if (is_string($key) && !empty($key))
            {
                $this->$key = $value;
            }
        }*/
        $this->rpcResponse = $rpcResponse;
    }
}