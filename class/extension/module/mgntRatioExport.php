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

                    /*
                     * Counts (All, Inclusive (without default-costs), Regular)
                     */
                    $tmp['count'] += $disposition['amount'];
                    $tmp['countInclusive'] = $disposition['price']['default_net'] == 0 ? $tmp['countInclusive']+$disposition['amount'] : $tmp['countInclusive'];
                    $tmp['countRegular'] = $disposition['price']['default_net'] == 0 ? $tmp['countRegular'] : $tmp['countRegular']+$disposition['amount'];

                    /*
                     * Turnovers
                     */
                    $tmp['sumRealTurnover'] += $disposition['price']['unit_net'] * $disposition['amount'];
                    $tmp['realTurnover'] = $tmp['sumRealTurnover'] / $tmp['count'];

                    $tmp['sumRegularTurnover'] += $disposition['price']['default_net'] * $disposition['amount'];
                    if ($tmp['countRegular'] > 0)
                    {
                        $tmp['realTurnover'] = $tmp['sumRealTurnover'] / $tmp['countRegular'];
                    }


                    /*
                     * External costs
                     */
                    if (isset($this->purchasePrices[$disposition['product']['norm']][$disposition['product']['pronr']]['externalCosts']))
                    {
                        $tmp['costsExternal'] = $this->purchasePrices[$disposition['product']['norm']][$disposition['product']['pronr']]['externalCosts'];
                    }
                    else if (isset($this->purchasePrices[$disposition['product']['norm']]['externalCosts']))
                    {
                        $tmp['costsExternal'] = $this->purchasePrices[$disposition['product']['norm']]['externalCosts'];
                    }
                    $tmp['sumCostsExternal'] = $tmp['costsExternal'] * $tmp['count'];

                    /*
                     * Working-time costs
                     */
                    if (isset($this->purchasePrices[$disposition['product']['norm']][$disposition['product']['pronr']]['workingTime']))
                    {
                        $tmp['workingTime'] = $this->purchasePrices[$disposition['product']['norm']][$disposition['product']['pronr']]['workingTime'] * 60;
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

                    //$product['costsExternal'] += round($this->purchasePrices['overheadCosts'] / $systemPointsSum * $product['systemPoints'],2);
                    $product['overheadRatio'] = round(100 / $systemPointsSum * $product['systemPoints'],2);
                    $product['overhead'] = round($this->purchasePrices['overheadCosts'] * $product['overheadRatio'] / 100, 2);

                    $product['sumCosts'] = $product['costsExternal']+$product['costsWorkingTime'];

                    $product['regularTurnover'] = $product['countRegular'] < 1 ?: $product['sumRegularTurnover'] / $product['countRegular'];

                    $product['regularGain'] = $product['regularTurnover'] - $product['sumCosts'];
                    $product['realGain'] = $product['realTurnover'] - $product['sumCosts'];

                    //Contributionmargin=Deckungsbeitrag
                    $product['db1']=$product['regularTurnover']-$product['costsExternal'];
                    $product['db2']=$product['db1']-$product['costsWorkingTime'];
                    $product['db3']=$product['db2']-$product['overhead'];
                    $product['DB3'] = $product['sumRealTurnover'] - ($product['costsExternal']+$product['costsWorkingTime']+$product['overhead'])*$product['count'];

                    // KnowdownPrice: +15%
                    $product['knockdownPrice'] = ($product['costsExternal'] + $product['costsWorkingTime'] + $product['overhead'])*1.15;
                }
            }
        }

        // We're done.
        return $this;
    }




    /**
     * Returns Export-CSV
     *
     * @return string
     */
    public function getCsvGe()
    {
        $csv = "Typ;Kurzbez.;Anzahl;Anzahl inkl.;VK-Listenpreis (€/Mon);Ø-VK (€/Mon);EK (€/Mon);db1 (-Fremdkosten);db2 (-Personal); db3 (-Infrastruktur); DB3; Niedrigst-VK; Orders\n";

        foreach ($this->data as $type => &$products)
        {
            ksort($products);
            foreach ($products as $name => &$product)
            {
                $csv .= "$type;";
                $csv .= "$name;";
                $csv .= "{$product['countRegular']};";
                $csv .= "{$product['countInclusive']};";
                $csv .= number_format($product['regularTurnover'], 2, ',', '.').'€;';
                $csv .= number_format($product['realTurnover'], 2, ',', '.').'€;';
                $csv .= number_format($product['costsExternal'], 2, ',', '.').'€;';
                $csv .= number_format($product['db1'], 2, ',', '.').'€;';
                $csv .= number_format($product['db2'], 2, ',', '.').'€;';
                $csv .= number_format($product['db3'], 2, ',', '.').'€;';
                $csv .= number_format($product['DB3'], 2, ',', '.').'€;';
                $csv .= number_format($product['knockdownPrice'], 2, ',', '.').'€;';
                $csv .= '"'.$product['ordersString'].'"';
                $csv .= "\n";
            }
            $csv .= " ; ; ; ; ; ; ; ; ; ; \n";
        }
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