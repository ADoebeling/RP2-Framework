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
    protected $order;

    /**
     * @var array various imported data
     */
    protected $data;

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
    public function getOrder()
    {
        return $this->order;
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
}