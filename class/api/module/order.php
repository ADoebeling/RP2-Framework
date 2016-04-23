<?php

namespace rpf\api\module;
use rpf\api\apiModule;


/**
 * !! DEPRECATED !!
 * DO NOT USE ANY MORE
 *
 * @package rpf\api\module
 */
class order extends apiModule
{

    protected $orders, $data;

    protected $disposition = array();

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


    /*public function loadOrdersAdresses()
    {
        $result = $this->system->call('bbCustomer::readEntry', ['return_array' => 1, 'return_orders' => 1, 'return_adress' => 1]);
        foreach ($result as $id => &$row)
        {
            $this->data[$row['cusnr']] = &$row;
        }
        return $this;
    }*/

    /**
     * @param (array) $param['accounting']
     * @param bool $runOnce
     * @return $this
     */
    public function loadAll($param = array(), $runOnce = true)
    {

        //if ($runOnce && !$this->runOnce(__METHOD__)) return $this;

        if (empty($param)) {
            $this->data = $this->getApi()->call('bbOrder::readEntry', ['return_array' => 1]); //, 'return_adress' => 1
        }

        else if (isset($param['accounting']))
        {
            $this->data = $this->system->call('bbOrder::readEntry', ['return_active' => 1, 'return_dispositions' => 1, 'return_account_entrys' => 1, 'return_array' => 1]); //, 'return_adress' => 1
        }
        return $this;
    }

    public function load($ordNr, $param = array(), $runOnce = true)
    {
        if ($runOnce && !$this->runOnce(__METHOD__)) return $this;

        if (empty($param)) {
            $this->data[$ordNr] = $this->system->call('bbOrder::readEntry', ['return_active' => 1, 'return_dispositions' => 1, 'return_customer' => 1, 'return_adress' => 1, 'ordnr' => $ordNr]);
        }
        return $this;
    }



    public function getDisposition($ordNr = false)
    {
        if ($ordNr !== false && isset($this->disposition[$ordNr]))
        {
            return $this->disposition[$ordNr];
        }
        else if (!empty($this->disposition))
        {
            return $this->disposition;
        }
        else
        {
            throw new \Exception('We don\'t have any dispositions', 404);
        }
    }






}