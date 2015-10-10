<?php namespace www1601com\df_rp\module;


require_once __DIR__.'/../apiModule.php';
class order extends apiModule
{

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
        if ($runOnce && !$this->runOnce(__METHOD__)) return $this;

        if (empty($param)) {
            $this->data = $this->system->call('bbOrder::readEntry', ['return_array' => 1]); //, 'return_adress' => 1
        }

        else if (isset($param['accounting']))
        {
            $this->data = $this->system->call('bbOrder::readEntry', ['return_active' => 1, 'return_dispositions' => 1, 'return_account_entrys' => 1, 'return_array' => 1]); //, 'return_adress' => 1
        }
        return $this;
    }







}