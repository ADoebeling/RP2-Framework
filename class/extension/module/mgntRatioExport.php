<?php namespace www1601com\df_rp\extension;

require_once __DIR__.'/../extensionModule.php';
class mgntRatioExport extends extensionModule
{
    /** @var array $purchasePrices all costs */
    protected $costs = array();
    
    /** @var array all sums */
    protected $sum = array();

    /** @var array all products */
    protected $product = array();

    /** @var array all totals */
    protected $totals = array();

    public function __construct(extension &$system)
    {
        parent::__construct($system);
        $this->data['costs'] =& $this->costs;
        $this->data['sum'] =& $this->sum;
        $this->data['product'] =& $this->product;
        $this->data['total'] =& $this->total;
    }
    
    /**
     * Set purchase-prices
     *
     * @param array $costs [Customer|Tariff|Tld|AddOn][name][externalCosts|workingTime] = X
     * @return $this
     */
    public function setPurchasePrices(array $prices)
    {
        $this->costs = $prices;
        return $this;
    }

    /**
     * Helper-function: getTypes
     *
     * @return array
     */
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
     * calcSums
     */
    protected function calcOverall()
    {
        foreach ($this->system->orders->loadAll(['accounting' => 1])->getData() as $order)
        {
            foreach ($order['dispositions'] as $dispo)
            {
                // Only count active orders
                if ((strtotime($dispo['account_end']) != 0 && strtotime($dispo['account_end']) < time())) {
                    continue;
                }

                $sum =& $this->sum[$dispo['product']['norm']][$dispo['product']['pronr']];

                /** @var int $p['count'] amount of products */
                $sum['count'];

                /** @var int $p['countBilled'] amount of products that can be billed directly */
                $sum['countBilled'];

                /** @var int $p['countUnbilled'] amount of products that can't be billed directly */
                $sum['countUnbilled'];

                /** @var float $sum['turnoverRegular'] Sum of all turnovers by default-price that could be billed directly*/
                $sum['turnoverRegular'];

                /** @var float $sum['turnoverReal'] Sum of all turnovers by real-price that can be billed directly */
                $sum['turnoverReal'];

                /** @var int $sum['systemPoints'] Sum of all taken system-points */
                $sum['systemPoints'];

                /** @var int $sum['systemPointsBilled'] Sum of all taken system-points that can be billed directly */
                $sum['systemPointsBilled'];

                /** @var int $sum['systemPointsUnbilled'] Sum of all taken system-points that can't be billed directly */
                $sum['systemPointsUnbilled'];

                /** @var float $sum['externalCosts'] Sum of all external costs */
                $sum['externalCosts'];

                /** @var float $sum['externalCostsBilled'] Sum of all external costs that can be billed directly */
                $sum['externalCostsBilled'];

                /** @var float $sum['externalCostsUnbilled'] Sum of all external costs that can't be billed directly */
                $sum['externalCostsUnbilled'];

                /** @var float $sum['workingTime'] Sum of all taken working-time in hours */
                $sum['workingTime'];

                /** @var float $sum['workingTimeBilled'] Sum of all taken working-time in hours that can be billed directly */
                $sum['workingTimeBilled'];

                /** @var float $sum['workingTimeUnbilled'] Sum of all taken working-time in hours that can't be billed directly */
                $sum['workingTimeUnbilled'];

                /** @var float $sum['workingTime'] Sum of all taken working-time-costs */
                $sum['workingTimeCosts'];

                /** @var float $sum['workingTimeBilled'] Sum of all taken working-time-costs that can be billed directly */
                $sum['workingTimeCostsBilled'];

                /** @var float $sum['workingTimeUnbilled'] Sum of all taken working-time-costs that can be billed directly */
                $sum['workingTimeCostsUnbilled'];

                /** @var float $sum['overheadCosts'] Sum of all overhead-costs */
                //$sum['overheadCosts'] = 0;

                /** @var float $sum['overheadCostsBilled'] Sum of all overhead-costs that can be billed directly */
                //$sum['overheadCostsBilled'] = 0;

                /** @var float $sum['overheadCostsUnbilled'] Sum of all overhead-costs that can't be billed directly */
                //$sum['overheadCostsUnbilled'] = 0;



                // External Costs can be stored at the product or at the product-group
                if (isset($this->costs[$dispo['product']['norm']][$dispo['product']['pronr']]['externalCosts']))
                {
                    $externalCosts = $this->costs[$dispo['product']['norm']][$dispo['product']['pronr']]['externalCosts'] * $dispo['count'];
                }
                else if (isset($this->costs[$dispo['product']['norm']]['externalCosts']))
                {
                    $externalCosts = $this->costs[$dispo['product']['norm']]['externalCosts'] * $dispo['count'];
                }
                else
                {
                    $externalCosts = 0;
                }

                // Working Time can be stored at the product or at the product-group
                if (isset($this->costs[$dispo['product']['norm']][$dispo['product']['pronr']]['workingTime']))
                {
                    $workingTime = $this->costs[$dispo['product']['norm']][$dispo['product']['pronr']]['workingTime'] * $dispo['count'];
                }
                else if (isset($this->costs[$dispo['product']['norm']]['workingTime']))
                {
                    $workingTime = $this->costs[$dispo['product']['norm']]['workingTime'] * $dispo['count'];
                }
                else
                {
                    $workingTime = 0;
                }

                /** @var array $purchasePrice TMP alias for $this->costs[$dispo['product']['norm']][$dispo['product']['pronr']] */
                $purchasePrice = !isset($this->costs[$dispo['product']['norm']][$dispo['product']['pronr']]) ?: $this->costs[$dispo['product']['norm']][$dispo['product']['pronr']];

                $systemPoints = isset($purchasePrice['systemPoints']) ?: $purchasePrice['systemPoints'] * $dispo['amount'];
                $externalCosts = !isset($purchasePrice['externalCosts']) ?: $purchasePrice['externalCosts'] * $dispo['amount'];
                $workingTimeCosts = !isset($this->costs['workingTimeCosts']) ?: $this->costs['workingTimeCosts'] * $workingTime;



                /*
                 * Billed
                 */
                if ($dispo['price']['default_net'] + $dispo['price']['unit_net'] != 0)
                {
                    $sum['turnoverRegular'] += $dispo['price']['default_net'] * $dispo['amount'];
                    $sum['turnoverReal'] += $dispo['price']['unit_net'] * $dispo['amount'];

                    $sum['countBilled'] += $dispo['amount'];
                    $sum['systemPointsBilled'] += $systemPoints;
                    $sum['externalCostsBilled'] += $externalCosts;
                    $sum['workingTimeBilled'] += $workingTime;
                    $sum['workingTimeCostsBilled'] += $workingTimeCosts;
                }

                /*
                 * Unbilled
                 */
                else
                {
                    $sum['countUnbilled'] += $dispo['amount'];
                    $sum['systemPointsUnbilled'] += $systemPoints;
                    $sum['externalCostsUnbilled'] += $externalCosts;
                    $sum['workingTimeUnbilled'] += $workingTime;
                    $sum['workingTimeCostsUnbilled'] += $workingTimeCosts;
                }

                /*
                 * Sums
                 */
                $sum['count'] = $sum['countBilled'] + $sum['countUnbilled'];
                $sum['systemPoints'] = $sum['systemPointsBilled'] + $sum['systemPointsUnbilled'];
                $sum['externalCosts'] = $sum['externalCostsBilled'] + $sum['externalCostsUnbilled'];
                $sum['workingTime'] = $sum['workingTimeBilled'] + $sum['workingTimeUnbilled'];
                $sum['workingTimeCosts'] = $sum['workingTimeCostsBilled'] + $sum['workingTimeCostsUnbilled'];
            }
        }

        return $this;
    }


    public function calcOverallOverhead()
    {
        if (empty($this->sum)) throw new \Exception('$this->sum[] is empty. You have to run ::calcOverall first AND should have some active dispositions', 424);

        foreach ($this->sum as $type => &$products)
        {
            foreach ($products as $id => &$sum)
            {
                /** @var float $sum ['overhead'] Sum of all taken overhead-costs */
                $sum['overheadCosts'] = 0;

                /** @var float $sum ['overheadBilled'] Sum of all taken overhead-costs that can be billed directly */
                $sum['overheadCostsBilled'] = 0;

                /** @var float $sum ['overheadUnbilled'] Sum of all taken overhead-costs that can't be billed directly */
                $sum['overheadCostsUnbilled'] = 0;

                if ($this->total['systemPoints'] > 0)
                {
                    $sum['overheadCosts'] = $this->costs['overheadCosts'] / $this->total['systemPoints'] * $sum['systemPoints'];
                    $sum['overheadCostsBilled'] = $this->costs['overheadCosts'] / $this->total['systemPoints'] * $sum['systemPointsBilled'];
                    $sum['overheadCostsUnbilled'] = $this->costs['overheadCosts'] / $this->total['systemPoints'] * $sum['systemPointsUnbilled'];
                }
            }
        }
        return $this;
    }


    public function calcOverallContributionMargin()
    {
        if (empty($this->data['sum'])) throw new \Exception('$this->sum[] is empty. You have to run ::calcOverall first AND should have some active dispositions', 424);

        foreach ($this->data['sum'] as $type => &$products)
        {
            foreach ($products as $id => &$sum)
            {
                $sum['unbilledCosts'] = $sum['externalCostsUnbilled']+$sum['workingTimeCostsUnbilled']+$sum['overheadCostsUnbilled'];

                $sum['cm1'] = $sum['turnoverRegular'] - $sum['externalCosts'];
                $sum['cm2'] = $sum['cm1'] - $sum['workingTimeCosts'];
                $sum['cm3'] = $sum['cm2'] - $sum['overheadCosts'];
                $sum['cm4'] = $sum['cm3'] - $sum['unbilledCosts'] * $sum['systemPoints'] / $this->total['systemPoints'];
                $sum['cm4Ratio'] = 100/$sum['turnoverRegular']*$sum['cm4'];
            }
        }
    }


    protected function calcProducts()
    {
        if (empty($this->sum)) throw new \Exception('$this->sum[] is empty. You have to run ::calcOverall first AND should have some active dispositions', 424);

        foreach ($this->sum as $type => &$products)
        {
            foreach ($products as $id => &$sum)
            {
                //Should always be true
                if ($sum['count'] > 0)
                {
                    /** @var string $p ['type'] type of the product */
                    $p['type'] = $type;

                    /** @var string (!) $p ['id'] id of the product */
                    $p['id'] = $id;

                    /** @var float $p ['costsExternal'] external product-costs */
                    $p['costsExternal'] = $sum['costsExternal'] / $sum['count'];

                    /** @var float $p ['costsWorkingTime'] working-time-costs per product */
                    $p['workingTimeCosts'] = $sum['workingTimeCosts'] / $sum['count'];


                }

                if ($sum['countBilled'] > 0)
                {
                    /** @var float $p ['turnoverRegular'] regular product-turnover */
                    $p['turnoverRegular'] = $sum['turnoverRegular'] / $sum['countBilled'];

                    /** @var float $p ['turnoverBilledAverage'] average product-turnover */
                    $p['turnoverBilledAverage'] = $sum['turnoverReal'] / $sum['countBilled'];

                    /** @var float $p ['externalCostsBilled'] billed external product-costs */
                    $p['externalCostsBilled'] = $sum['externalCostsBilled'] / $sum['countBilled'];

                    /** @var float $p ['workingTimeCostsBilled'] billed working-time-costs per product */
                    $p['workingTimeCostsBilled'] = $sum['workingTimeCosts'] / $sum['countBilled'];

                    /** @var float $p ['costsOverheadBilled'] billed overhead-costs per product */
                    $p['costsOverheadBilled'] = $sum['costOverhead'];

                    $p['cm1'] = $sum['cm1'] / $sum['countBilled'];
                    $p['cm2'] = $sum['cm2'] / $sum['countBilled'];
                    $p['cm3'] = $sum['cm3'] / $sum['countBilled'];
                    $p['cm4'] = $sum['cm4'] / $sum['countBilled'];
                    $p['cm4Ratio'] = $sum['cm4Ratio'];
                }

                if ($sum['countUnbilled'] > 0)
                {
                    /** @var float $p ['costsExternalBilled'] unbilled external product-costs */
                    $p['externalCostsUnbilled'] = $sum['externalCostsUnbilled'] / $sum['countUnbilled'];
                }


                /** @var float $p ['costsOverheadBilled'] billed overhead-costs per product */
                //$p['costsOverheadBilled'] = 0;

                /** @var float $p ['costsForUnbilled'] cleared costs for unbilled products per product */
                //$p['costsForUnbilled'] = 0;
            }
            return $this;
        }
    }





    protected function calcTotal()
    {
        foreach ($this->sum as $type => &$products)
        {
            foreach ($products as $name => &$product)
            {
                foreach ($product as $key => &$val)
                {
                    $this->totals[$key] += $val;
                }
            }
        }
        return $this;
    }






    public function calc()
    {
        $this->calcOverall();
        $this->calcTotal();

        $this->calcOverallOverhead();
        $this->calcTotal();

        $this->calcOverallContributionMargin();
        $this->calcTotal();

        $this->calcProducts();
        $this->calcTotal();

        return $this;
    }






    /**
     * Returns Export-CSV
     *
     * @return string
     */
    public function getCsvGe()
    {
        $csv = "Typ;Kurzbez.;Anzahl;Anzahl inkl.;VK-Listenpreis (€/Mon);Ø-VK (€/Mon);EK (€/Mon);db1 (-Fremdkosten);db2 (-Personal); db3 (-Infrastruktur); DB3; DB4 (Verrechnete Inklusivleistungen); Niedrigst-VK; Orders\n";

        foreach ($this->data as $type => &$products)
        {
            ksort($products);
            foreach ($products as $name => &$product)
            {
                $csv .= "$type;";
                $csv .= "$name;";
                $csv .= "{$product['countBilled']};";
                $csv .= "{$product['countUnbilled']};";
                $csv .= number_format($product['regularTurnover'], 2, ',', '.').'€;';
                $csv .= number_format($product['realTurnover'], 2, ',', '.').'€;';
                $csv .= number_format($product['costsExternal'], 2, ',', '.').'€;';
                $csv .= number_format($product['db1'], 2, ',', '.').'€;';
                $csv .= number_format($product['db2'], 2, ',', '.').'€;';
                $csv .= number_format($product['db3'], 2, ',', '.').'€;';
                $csv .= number_format($product['DB3'], 2, ',', '.').'€;';
                $csv .= number_format($product['DB4'], 2, ',', '.').'€;';
                $csv .= number_format($product['knockdownPrice'], 2, ',', '.').'€;';
                $csv .= '"'.$product['ordersString'].'"';
                $csv .= "\n";
            }
            $csv .= " ; ; ; ; ; ; ; ; ; ; ; \n";
        }
        return $csv;
    }

    /**
     * Stores the calculated values to mysql
     *
     * @return $this
     */
    public function store()
    {
        return $this;
    }


    public function getCsvFull()
    {
        $csv = "";
        foreach ($this->data as $type => &$products)
        {
            ksort($products);
            foreach ($products as $name => &$product)
            {
                //ksort($product);
                if (empty($csv))
                {
                    foreach ($product as $name => $val)
                    {
                        unset($val);
                        $csv .= "$name; ";
                    }
                    $csv .= "\n";
                }

                foreach ($product as $name => $val)
                {
                    $csv .= "$val; ";
                }
                $csv .= "\n";
            }
        }
        return $csv;
    }


    /**
     * Send application/csv-header and download csv
     *
     * @param bool $filename
     * @return $this
     */
    public function sendDownloadCsv($filename = false, $source = 'ge')
    {
        $filename = $filename !== false ? $filename : 'Hosting_MgntRatioExport_'.date('ymd').'_1SRV';
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=\"$filename\";");

        if ($source == 'ge')
        {
            echo $this->getCsvGe();
        }
        else if ($source == 'full')
        {
            echo $this->getCsvFull();
        }
        return $this;
    }


}