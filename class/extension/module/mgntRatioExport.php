<?php namespace www1601com\df_rp\extension;

require_once __DIR__.'/../extensionModule.php';
class mgntRatioExport extends extensionModule
{
    private $purchasePrices = array();

    protected $data;

    /**
     * Set purchase-prices
     *
     * @param array $costs [Customer|Tariff|Tld|AddOn][name][externalCosts|workingTime] = X
     * @return $this
     */
    public function setPurchasePrices(array $prices)
    {
        $this->purchasePrices = $prices;
        return $this;
    }


    public function getTypes()
    {
        $types = array();
        foreach ($data = $this->getData() as $id => $row)
        {
            $types[$id] = array();
            foreach ($row as $id2 => $row2)
            {
                array_push($types[$id], $id2);
            }
        }
        return $types;
    }

    /**
     * Loads data from orders::loadAll() and parses them
     *
     * @return $this
     */
    public function loadData()
    {
        // Sum of all system-points
        $systemPointsSum = 0;

        foreach ($this->system->orders->loadAll(['accounting' => 1])->getData() as $order)
        {
            foreach($order['dispositions'] as $disposition)
            {

                if ((strtotime($disposition['account_end']) == 0 || strtotime($disposition['account_end']) > time()))
                {
                    /*
                     * Use $tmp as alias
                     */
                    $tmp =& $this->data[$disposition['product']['norm']][$disposition['product']['pronr']];

                    /*
                     * Orders
                     */
                    $tmp['orders'][$order['ordnr']] = 1;

                    //print_r($order);
                    //die();

                    /*
                     * Counts (All, Inclusive (without default-costs), Regular)
                     */
                    $tmp['count'] += $disposition['amount'];
                    $tmp['countInclusive'] = $disposition['price']['default_net'] == 0 ? $tmp['countInclusive']+$disposition['amount'] : $tmp['countInclusive'];
                    $tmp['countRegular'] = $disposition['price']['default_net'] == 0 ? $tmp['countRegular'] : $tmp['countRegular']+$disposition['amount'];

                    /*
                     * Turnovers
                     */
                    $tmp['sumRealTurnover'] += round($disposition['price']['unit_net'] * $disposition['amount'],2);
                    $tmp['realTurnover'] = $tmp['sumRealTurnover'] / $tmp['count'];

                    $tmp['sumRegularTurnover'] += $disposition['price']['default_net'] * $disposition['amount'];
                    if ($tmp['countRegular'] > 0)
                    {
                        $tmp['realTurnover'] = round($tmp['sumRealTurnover'] / $tmp['countRegular'],2);
                    }


                    /*
                     * External costs
                     */
                    $tmp['costsExternal'] = round($this->purchasePrices[$disposition['product']['norm']][$disposition['product']['pronr']]['externalCosts'], 2);
                    $tmp['sumCostsExternal'] = $tmp['costsExternal'] * $tmp['count'];

                    /*
                     * Working-time costs
                     */
                    if (isset($this->purchasePrices[$disposition['product']['norm']][$disposition['product']['pronr']]['workingTime']))
                    {
                        $tmp['workingTime'] = round($this->purchasePrices[$disposition['product']['norm']][$disposition['product']['pronr']]['workingTime']*60,1);
                        $tmp['costsWorkingTime'] = round($this->purchasePrices[$disposition['product']['norm']][$disposition['product']['pronr']]['workingTime'] * $this->purchasePrices['workingTimeCosts'],2);
                    }
                    elseif (isset($this->purchasePrices[$disposition['product']['norm']]['workingTime']))
                    {
                        $tmp['workingTime'] = round($this->purchasePrices[$disposition['product']['norm']]['workingTime']*60,1);
                        $tmp['costsWorkingTime'] = round($this->purchasePrices[$disposition['product']['norm']]['workingTime'] * $this->purchasePrices['workingTimeCosts'],2);
                    }
                    $tmp['sumCostsWorkingTime'] = round($tmp['costsWorkingTime']*$tmp['count'],2);


                    /*
                     * Overhead-costs
                     */
                    if (isset($this->purchasePrices[  $disposition['product']['norm']  ][  $disposition['product']['pronr']  ]['systemPoints']))
                    {
                        $systemPointsSum += $tmp['systemPoints'];
                        $tmp['systemPoints'] = $this->purchasePrices[$disposition['product']['norm']][$disposition['product']['pronr']]['systemPoints'] * $disposition['amount'];
                    }
                    else
                    {
                        $tmp['systemPoints'] = 0;
                    }
                }
            }
        }



        /*
         * Let's add the overhead-costs, based on the system-points and calculate a little bit
         */
        if ($systemPointsSum > 0)
        {
            foreach ($this->data as $type => &$products)
            {
                foreach ($products as &$product)
                {
                    foreach ($product['orders'] as $key => $val)
                    {
                        $product['ordersString'] .= "$key ";
                    }

                    $product['costsExternal'] += round($this->purchasePrices['overheadCosts'] / $systemPointsSum * $product['systemPoints'],2);
                    $product['overheadRatio'] = round(100 / $systemPointsSum * $product['systemPoints'],2);

                    $product['sumCosts'] = $product['costsExternal']+$product['costsWorkingTime'];

                    $product['regularTurnover'] = round($product['sumRegularTurnover'] / $product['countRegular'],2);

                    $product['regularGain'] = $product['regularTurnover'] - $product['sumCosts'];
                    $product['realGain'] = $product['realTurnover'] - $product['sumCosts'];


                }
            }
        }

        // We're done.
        return $this;
    }

    /**
     * @return array[customer] = array(company => X, firstName => X, lastName => X, orders => X, regularTurnover => X, currentTurnover => X, costs = x]
     */
    public function getCustomers()
    {
        $name = "{$row['lastName']}, {$row['firstName']}";
        $name = empty($row['customer']) ? $name : "{$row['company']} ($name)";
    }

    /**
     * @return array[tariff] = array(countRegular => X, regularTurnover => X, realTurnover => X, costs = x)
     */
    public function getTariffs()
    {
        $data = $this->data['tariff'];
    }

    /**
     * @return float $sum (sum of all tariff and addon-turnovers)
     */
    /*
    protected function getTariffAddOnTurnover()
    {
        $data = $this->getData()['tariff'];
        foreach ($data as $id => $row)
        {
            $sum += $row['sumRealTurnover'];
        }
        // TODO

        $data = $this->getData()['add-on'];
        foreach ($data as $id => $row)
        {
            $sum += $row['sumRealTurnover'];
        }
        return $sum;
    }
    */


    public function loadSystemPoints()
    {
        $systemPointsSum = 0;

        // Calc the sum
        foreach ($this->data as $id => &$row)
        {
            $systemPointsSum += $row['systemPoints'];
        }


        return $this;
    }

    /**
     * @return array[addOn] = array(count => X, regularTurnover => X, realTurnover => X, costs = x)
     */
    public function getAddOns()
    {
        foreach ($this->data['add-on'] as $id => &$row)
        {
            /*$overhead = round($this->purchasePrices['overheadCosts'] / $this->getTariffAddOnTurnover() * $row['sumRealTurnover'],2);
            $row['costsExternal'] += round($overhead / $row['count'],2);
            $row['sumCostsExternal'] += $overhead;
            $row['sumCosts']+= $overhead;
            $row['sumGain'] -= $overhead;*/
        }
        return $data;
    }

    /**
     * @return array[ssl] = array(count => X, regularTurnover => X, realTurnover => X, costs = x)
     */
    public function getSsl()
    {

    }

    /**
     * @return array[exchange] = array(count => X, regularTurnover => X, realTurnover => X, costs = x)
     */
    public function getExchange()
    {

    }

    /**
     * @return array[tld] = array(count => X, regularTurnover => X, realTurnover => X, costs = x)
     */
    public function getTlds()
    {
    }



    /**
     * Returns Export-CSV
     *
     * @return string
     */
    public function getCsv()
    {
        $csv = "Type;\tName;\tCount Regular;\tCount Inclusive;\tRegular Turnover (€/Mon);\tReal Turnover (€/Mon);\tCosts External (€/Mon);\tWorking Time (Min/Mon);\tSum Costs (€/Mon);\tRegular Gain (€/Mon); Real Gain (€/Mon); Orders\n";

        /*foreach ($this->getCustomers() as $name => &$row) {
            $csv .= "Customer; $name; {$row['count']}; {$row['regularTurnover']}; {$row['currentTurnover']}; {$row['costs']}\n";
        }

        $csv .= "\n\n";

        foreach ($this->getTariffs() as $name => &$row) {
            $csv .= "Tariff; $name; {$row['count']}; {$row['regularTurnover']}; {$row['currentTurnover']}; {$row['costs']}\n";
        }

        $csv .= "\n\n";

        foreach ($this->getTlds() as $name => &$row) {
            $csv .= "TLD; $name; {$row['count']}; {$row['regularTurnover']}; {$row['currentTurnover']}; ; {$row['costs']}\n";
        }

        $csv .= "\n\n";

        foreach ($this->getAddOns() as $name => &$row) {
            $csv .= "AddOn; $name; {$row['count']}; {$row['regularTurnover']}; {$row['currentTurnover']}; {$row['costs']}\n";
        }*/

        foreach ($this->data as $type => &$products)
        {
            ksort($products);
            foreach ($products as $name => &$product)
            {
                $csv .= "$type;";
                $csv .= "$name;";
                $csv .= "{$product['countRegular']};";
                $csv .= "{$product['countInclusive']};";
                $csv .= number_format($product['regularTurnover'], 2, ',', '.').'€ ;';
                $csv .= number_format($product['realTurnover'], 2, ',', '.').'€ ;';
                $csv .= number_format($product['costsExternal'], 2, ',', '.').'€ ;';
                $csv .= number_format($product['workingTime'], 2, ',', '.').';';
                $csv .= number_format($product['sumCosts'], 2, ',', '.').'€ ;';
                $csv .= number_format($product['regularGain'], 2, ',', '.').'€ ;';
                $csv .= number_format($product['realGain'], 2, ',', '.').'€ ;';
                $csv .= '"'.$product['ordersString'].'"';
                $csv .= "\n";
            }
            $csv .= " ; ; ; ; ; ; ; ; ; ; ;\n";

        }
        //$csv = str_replace('.', ',', $csv);
        return $csv;
    }


    /**
     * Send application/csv-header and download csv
     *
     * @param bool $filename
     * @return $this
     */
    public function sendDownloadCsv($filename = false)
    {
        $filename = $filename !== false ? $filename : '1601_Hosting_MgntRatioExport_'.date('ymd').'_1SRV';
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=\"$filename\";");

        echo $this->getCsv();
        return $this;
    }



}