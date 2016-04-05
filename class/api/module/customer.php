<?php

namespace rpf\api\module;
use rpf\api\apiModule;

class customer extends apiModule{

    public function loadOrdersAdresses()
    {
        $result = $this->system->call('bbCustomer::readEntry', ['return_array' => 1, 'return_orders' => 1, 'return_adress' => 1]);
        foreach ($result as $id => &$row)
        {
            $this->data[$row['cusnr']] = &$row;
        }
        return $this;
    }



}