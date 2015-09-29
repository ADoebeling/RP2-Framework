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

    public function loadAll()
    {
        $this->order = $this->system->call('bbOrder::readEntry', ['return_array' => 1]); //, 'return_adress' => 1
        return $this;
    }





}