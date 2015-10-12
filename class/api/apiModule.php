<?php

namespace www1601com\df_rp\module;
use www1601com\df_rp\api;

/**
 * Model for all API-Modules
 *
 * @package www1601com\df_rp\module
 */
class apiModule {

    /**
     * @var object api
     */
    protected $system;

    /**
     * @var array $order['oeid'][$key] = [$value]
     */
    protected $orders;

    /**
     * @var array various imported data
     */
    protected $data;

    /**
     * @var array Stores all already executed methods
     */
    protected $runOnce = [];

    /**
     * Build the class-structure
     *
     * @param api $system
     */
    public function __construct(api &$system)
    {
        $this->system = &$system;
    }

    /**
     * Returns a array with the previous load data, sorted by oeid
     *
     * @return array $order['oeid'][$key] = [$value]
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Returns a array with the previous load data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    public function runOnce($method)
    {
        if (!isset($this->runOnce[$method]))
        {
            $this->runOnce[$method] = 1;
            return true;
        }
        else
        {
            $this->runOnce[$method]++;
            return false;
        }
    }
}