<?php namespace www1601com\df_rp\module;


require_once __DIR__.'/../apiModule.php';
class order extends apiModule
{

    protected $disposition = array();

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

    public function load($ordNr, $param = array(), $runOnce = true)
    {
        if ($runOnce && !$this->runOnce(__METHOD__)) return $this;

        if (empty($param)) {
            $this->data[$ordNr] = $this->system->call('bbOrder::readEntry', ['return_active' => 1, 'return_dispositions' => 1, 'return_customer' => 1, 'return_adress' => 1, 'ordnr' => $ordNr]);
        }
        return $this;
    }

    public function loadDisposition($orderId, $param = ['view' => 'main', 'return_active' => true, 'return_accountable' => true])
    {
        // view => main and [so_type] are not part of the api-documentation
        if ($runOnce && !$this->runOnce(__METHOD__)) return $this;
        $param['oeid'] = $orderId;

        $result = $this->system->call('bbOrder::readDisposition', $param);

        /*
         * Patch for stupid/none implemented exchange // df-ticket #5402091 / #5401975
         */
        if (strpos($result['so_type'], 'hosting_zusatz_exchange_') === 0)
        {
            $regex = "/^(\\S*@\\S*\\.\\S{2,8})/";
            preg_match($regex, $result['name'], $matches);
            $result['_patch_exchange_account'] = $matches[1];
        }

        /*
         * Patch against stupid/none implemented ssl-certificate // df-ticket #5401975
         */
        else if($result['so_type'] == 'ssl')
        {
            $regEx = "/(\\S*\\.\\S*\\.\\S*)/";
            preg_match($regEx, $row['descr'], $domain);
            $result['_patch_ssldomain'] = $domain[1];
        }

        $this->disposition[$orderId] = $result;
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