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

    /** @var array all total */
    protected $total = array();

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

                /** @var float $sum['turnoverBilled'] Sum of all turnovers by real-price that can be billed directly */
                $sum['turnoverBilled'];

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

                /** @var array all orders */

                //print_r($order['ordnr']); die();

                $sum['orders'][$order['ordnr']] += 1;


                // External Costs can be stored at the product or at the product-group
                if (isset($this->costs[$dispo['product']['norm']][$dispo['product']['pronr']]['externalCosts']))
                {
                    $externalCosts = $this->costs[$dispo['product']['norm']][$dispo['product']['pronr']]['externalCosts'] * $dispo['amount'];
                }
                else if (isset($this->costs[$dispo['product']['norm']]['externalCosts']))
                {
                    $externalCosts = $this->costs[$dispo['product']['norm']]['externalCosts'] * $dispo['amount'];
                }
                else
                {
                    $externalCosts = 0;
                }

                // Working Time can be stored at the product or at the product-group
                if (isset($this->costs[$dispo['product']['norm']][$dispo['product']['pronr']]['workingTime']))
                {
                    $workingTime = $this->costs[$dispo['product']['norm']][$dispo['product']['pronr']]['workingTime'] * $dispo['amount'];
                }
                else if (isset($this->costs[$dispo['product']['norm']]['workingTime']))
                {
                    $workingTime = $this->costs[$dispo['product']['norm']]['workingTime'] * $dispo['amount'];
                }
                else
                {
                    $workingTime = 0;
                }

                /** @var array $purchasePrice TMP alias for $this->costs[$dispo['product']['norm']][$dispo['product']['pronr']] */
                $purchasePrice = !isset($this->costs[$dispo['product']['norm']][$dispo['product']['pronr']]) ?: $this->costs[$dispo['product']['norm']][$dispo['product']['pronr']];

                $systemPoints = !isset($purchasePrice['systemPoints']) ?: $purchasePrice['systemPoints'] * $dispo['amount'];
                $workingTimeCosts = !isset($this->costs['workingTimeCosts']) ?: $this->costs['workingTimeCosts'] * $workingTime;



                /*
                 * Billed
                 */
                if ($dispo['price']['default_net'] + $dispo['price']['unit_net'] != 0)
                {
                    $sum['turnoverRegular'] += $dispo['price']['default_net'] * $dispo['amount'];
                    $sum['turnoverBilled'] += $dispo['price']['unit_net'] * $dispo['amount'];

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
                    $sum['overheadCostsBilled'] = $this->costs['overheadCosts'] / $this->total['systemPointsBilled'] * $sum['systemPointsBilled'];
                    $sum['overheadCostsUnbilled'] = $this->costs['overheadCosts'] / $this->total['systemPointsUnbilled'] * $sum['systemPointsUnbilled'];

                    $sum['unbilledCosts'] = $sum['externalCostsUnbilled']+$sum['workingTimeCostsUnbilled']+$sum['overheadCostsUnbilled'];
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
                $sum['unbilledCostsShare'] = $this->total['unbilledCosts'] / $this->total['systemPointsBilled'] * $sum['systemPointsBilled'];

                $sum['cm1'] = $sum['turnoverRegular'] - $sum['externalCosts'];
                $sum['cm2'] = $sum['cm1'] - $sum['workingTimeCosts'];
                $sum['cm3'] = $sum['cm2'] - $sum['overheadCostsBilled'];
                $sum['cm4'] = $sum['cm3'] - ($sum['unbilledCostsShare'] * $sum['systemPointsBilled'] / $this->total['systemPointsBilled']);

                $sum['cm4Ratio'] = 0;
                if ($sum['turnoverRegular'] > 0)
                {
                    $sum['cm4Ratio'] = 100/$sum['turnoverRegular']*$sum['cm4'];
                }
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
                $p =& $this->product[$type][$id];

                /** @var string $p ['type'] type of the product */
                $p['type'] = $type;

                /** @var string (!) $p ['id'] id of the product */
                $p['id'] = $id;

                /** @var float $p ['externalCosts'] external product-costs */
                $p['externalCosts'] = 0;

                /** @var float $p ['costsWorkingTime'] working-time-costs per product */
                $p['workingTimeCosts'] = 0;

                /** @var int */
                $p['countBilled'] = 0;

                /** @var int */
                $p['countUnbilled'] = 0;

                /** @var float $p ['turnoverRegular'] regular product-turnover */
                $p['turnoverRegular'] = 0;

                /** @var float $p ['turnoverBilledAverage'] average product-turnover */
                $p['turnoverBilled'] = 0;

                /** @var float $p ['externalCostsBilled'] billed external product-costs */
                $p['externalCosts'] = 0;

                /** @var float $p ['workingTimeCostsBilled'] billed working-time-costs per product */
                $p['workingTimeCosts'] = 0;

                /** @var float $p ['workingTime'] billed working-time per product */
                $p['workingTime'] = 0;

                /** @var float $p ['costsOverheadBilled'] billed overhead-costs per product */
                $p['overheadCosts'] = 0;

                $p['cm1'] = 0;
                $p['cm2'] = 0;
                $p['cm3'] = 0;
                $p['cm4'] = 0;
                $p['cm4Ratio'] = 0;

                $p['bestPrice'] = 0;

                if ($sum['countBilled'] > 0)
                {
                    $p['countBilled'] = $sum['countBilled'];
                    $p['turnoverRegular'] = $sum['turnoverRegular'] / $sum['countBilled'];
                    $p['turnoverBilled'] = $sum['turnoverBilled'] / $sum['countBilled'];
                    $p['externalCosts'] = $sum['externalCostsBilled'] / $sum['countBilled'];
                    $p['workingTime'] = $sum['workingTime'] / $sum['count'];
                    $p['workingTimeCosts'] = $sum['workingTimeCosts'] / $sum['countBilled'];
                    $p['overheadCosts'] = $sum['overheadCosts'] / $sum['countBilled'];

                    //$p['unbilledCosts'] = $this->total['unbilledCosts'] / $sum['countBilled'];

                    $p['cm1'] = $sum['cm1'] / $sum['countBilled'];
                    $p['cm2'] = $sum['cm2'] / $sum['countBilled'];
                    $p['cm3'] = $sum['cm3'] / $sum['countBilled'];
                    $p['cm4'] = $sum['cm4'] / $sum['countBilled'];
                    $p['cm4Ratio'] = $sum['cm4Ratio'];

                    $p['bestPrice'] = ($p['externalCosts']+$p['workingTimeCosts']+$p['overheadCosts']+$p['unbilledCosts'])*1.15;
                }
                else
                {
                    $p['turnoverRegular'] = $sum['turnoverRegular'] / $sum['countUnbilled'];
                    $p['turnoverBilled'] = $sum['turnoverUnbilled'] / $sum['countUnbilled'];
                    $p['externalCosts'] = $sum['externalCostsUnbilled'] / $sum['countUnbilled'];
                    $p['workingTime'] = $sum['workingTime'] / $sum['count'];
                    $p['workingTimeCosts'] = $sum['workingTimeCosts'] / $sum['countUnbilled'];
                    $p['overheadCosts'] = $sum['overheadCosts'] / $sum['countUnbilled'];
                    $p['unbilledCosts'] = $sum['unbilledCosts'] / $sum['countUnbilled'];
                    $p['cm1'] = $sum['cm1'] / $sum['countUnbilled'];
                    $p['cm2'] = $sum['cm2'] / $sum['countUnbilled'];
                    $p['cm3'] = $sum['cm3'] / $sum['countUnbilled'];
                    $p['cm4'] = $sum['cm4'] / $sum['countUnbilled'];
                    $p['cm4Ratio'] = $sum['cm4Ratio'];
                }

                if ($sum['countUnbilled'] > 0)
                {
                    $p['countUnbilled'] = $sum['countUnbilled'];

                }

                foreach ($sum['orders'] as $ordnr => $count) {
                    $p['orders'] .= "$ordnr, ";
                }
            }
        }
        return $this;
    }

    /**
     * Calc all total-sums for each category (systemPoints, turnovers, ...)
     *
     * @return $this
     */
    protected function calcTotal()
    {
        $this->total = array(); // Never delete that line. I've searched this bug for hours

        foreach ($this->sum as $type => &$products)
        {
            foreach ($products as $name => &$product)
            {
                foreach ($product as $key => &$val)
                {
                    if (is_int($val) OR is_float($val))
                    {
                        $this->total[$key] += $val;
                    }
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
        $csv =
            'Produkttyp; '.
            'Produktname; '.
            'Anzahl; '.
            'Anzahl (>0 €); '.

            'Ø-Listenpreis (Stk./Mon.); '.
            'Ø-Verkaufspreis (Stk./Mon.); '.

            'Fremdkosten (Stk./Mon.); '.
            'Personalaufwand (Stunden/Jhr.); '.

            'db1 (Listenpreis-Fremdk. / Stk./Mon.); '.
            'db2 (-Personalk. / Stk./Mon.); '.
            'db3 (-kalk. Infrastr. / Stk./Mon.); '.
            'db4 (-ver. Inklusivleistungen / Stk./Mon.); '.

            'Niedrigstpreis (Kosten+15% / Stk./Mon.); '.
            'Marge; '.

            'Umsatz (Gesamt/Mon.); '.
            'DB4 (=Gesamtertrag/Mon.); '.

            'RP2-Auftäge'.
            "\n\n";

        foreach ($this->data['product'] as $type => &$products)
        {
            ksort($products);
            foreach ($products as $name => &$product)
            {
                $csv .= "$type;";
                $csv .= "$name;";

                $csv .= $product['countBilled'].'; ';
                $csv .= $product['countUnbilled'].'; ';

                $csv .= self::getEuroFormattedCsvColumn($product['turnoverRegular']);
                $csv .= self::getEuroFormattedCsvColumn($product['turnoverBilled']);

                $csv .= self::getEuroFormattedCsvColumn($product['externalCosts']);

                // mktime looks stupid here, but I needed hour 0
                $csv .= date("H:i", mktime(0,0,0)+$product['workingTime']*60*60*12) .'; ';

                $csv .= self::getEuroFormattedCsvColumn($product['cm1']);
                $csv .= self::getEuroFormattedCsvColumn($product['cm2']);
                $csv .= self::getEuroFormattedCsvColumn($product['cm3']);
                $csv .= self::getEuroFormattedCsvColumn($product['cm4']);

                $csv .= self::getEuroFormattedCsvColumn($product['bestPrice']);
                $csv .= number_format($product['cm4Ratio'], 2, ',', '.').' %; ';

                $csv .= self::getEuroFormattedCsvColumn($this->sum[$type][$name]['turnoverReal']);
                $csv .= self::getEuroFormattedCsvColumn($this->sum[$type][$name]['cm4']);

                $csv .= $product['orders'];
                $csv .= "\n";
            }
            $csv .= "\n";
        }

        //throw new \Exception("bis hier und nicht weiter");
        return $csv;
    }

    public static function getEuroFormattedCsvColumn($float)
    {
        return number_format($float, 2, ',', '.').' €; ';
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
        foreach ($this->data['sum'] as $type => &$products)
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
                    if (is_float($val))
                    {
                        $val = number_format($val, 2, ',', '.').' €';
                    }
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

        if ($source == 'ge')
        {
            $csv = $this->getCsvGe();
        }
        else if ($source == 'full')
        {
            $csv = $this->getCsvFull();
        }

        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=\"$filename\";");
        echo $csv;
        return $this;
    }


}