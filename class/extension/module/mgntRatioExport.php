<?php

namespace rpf\extension\module;

/**
 * class mgntRatioExport
 *
 * Calculates the contribution margin 1 - 5 of all products managed in the rp2.
 * Supports CSV-Export and in future mysql-storage
 *
 * --------------------------------------------------------------------------------------------------------------------
 *
 * selling price
 * -  external costs
 * = cm1
 *
 * - personal costs
 * = cm2
 *
 * - overheads direct (server infrastructure, ...)
 * - overheads indirect (costs for other free-of-charge products)
 * = cm3
 *
 * x sales volume
 * - discounts
 * = CM4 (overall)
 *
 * - costs for unbilled products of this type
 * + indirect overhead-credit for unbilled products of this type
 * = CM5
 *
 * --------------------------------------------------------------------------------------------------------------------
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @license cc-by-sa - http://creativecommons.org/licenses/by-sa/4.0/
 * @link https://www.xing.com/profile/Andreas_Doebeling/
 * @link https://github.com/ADoebeling
 *
 * @package system\extension
 *
 * @version 0.1.151021_dev_1ad
 */
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

    /**
     * Build the $data-array
     *
     * @param extension $system
     */
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
     * @param array $costs [tariff|domain|add-on|ext][pronr][externalCosts|workingTime|systemPoints] = X
     * @return $this
     */
    public function setPurchasePrices(array $prices)
    {
        $this->costs = $prices;
        return $this;
    }


    /**
     * Adds the sum of count, turnover, systemPoints, ... and stores them to
     * $this->sum[$type][$name][turnoverRegular] = X
     *
     * @return $this
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

                /** @var array all orders */
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
                $purchasePrice = isset($this->costs[$dispo['product']['norm']][$dispo['product']['pronr']]) ? $this->costs[$dispo['product']['norm']][$dispo['product']['pronr']] : 0;

                $systemPoints = isset($purchasePrice['systemPoints']) ? $purchasePrice['systemPoints'] * $dispo['amount'] : 0;
                $workingTimeCosts = isset($this->costs['workingTimeCosts']) ? $this->costs['workingTimeCosts'] * $workingTime : 0;


                /*
                 * Billed
                 */
                if ($dispo['price']['default_net'] != 0) // + $dispo['price']['unit_net']
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

    /**
     * Shares the overhead-costs to each product by systemPoints and stores them to
     * $this->sum[$type][$name][overheadCosts|overheadCostsBilled|...] = X
     *
     * @return $this
     * @throws \Exception
     */
    protected function calcOverallOverhead()
    {
        if (empty($this->sum)) throw new \Exception('$this->sum[] is empty. You have to run ::calcOverall first AND should have some active dispositions', 424);

        foreach ($this->sum as $type => &$products)
        {
            foreach ($products as $id => &$sum)
            {
                /** @var float $sum[discount] */
                $sum['discount'] = $sum['turnoverRegular']-$sum['turnoverBilled'];

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
                }

                /** @var float $sum[unbilledCosts] */
                $sum['unbilledCosts'] = $sum['externalCostsUnbilled']+$sum['workingTimeCostsUnbilled']+$sum['overheadCostsUnbilled'];
            }
        }
        return $this;
    }

    /**
     * Calculates the contribution-margin for all products and stores them to
     * $this->sum[$type][$name][cmX] = Y
     *
     * @throws \Exception
     * @return $this
     */
    protected function calcOverallContributionMargin()
    {
        if (empty($this->data['sum'])) throw new \Exception('$this->sum[] is empty. You have to run ::calcOverall first AND should have some active dispositions', 424);

        foreach ($this->data['sum'] as $type => &$products)
        {
            foreach ($products as $id => &$sum)
            {
                if ($sum['turnoverRegular'] > 0)
                {
                    $sum['unbilledCostsShare'] = $this->total['unbilledCosts'] / $this->total['systemPointsBilled'] * $sum['systemPointsBilled'];

                    $sum['cm1'] = $sum['turnoverRegular'] - $sum['externalCostsBilled'];
                    $sum['cm2'] = $sum['cm1'] - $sum['workingTimeCostsBilled'];
                    $sum['cm3'] = $sum['cm2'] - $sum['overheadCostsBilled'] - $sum['unbilledCostsShare'];
                    $sum['cm4'] = $sum['cm3'] - $sum['discount'];
                    $sum['cm5'] = $sum['cm4'];
                    $sum['displayUnbilledCosts'] = $sum['unbilledCosts'];
                    $sum['margin'] = 100/$sum['turnoverRegular']*$sum['cm3'];
                }
                else
                {
                    $sum['cm1'] = $sum['turnoverRegular'] - $sum['externalCostsUnbilled'];
                    $sum['cm2'] = $sum['cm1'] - $sum['workingTimeCostsUnbilled'];
                    $sum['cm3'] = $sum['cm2'] - $sum['overheadCostsUnbilled'];
                    $sum['cm4'] = $sum['cm3'] - $sum['discount'];
                    $sum['cm5'] = $sum['cm4'] + $sum['unbilledCosts'];
                    $sum['displayUnbilledCosts'] = 0;
                    $sum['margin'] = 0;
                }
                $sum['displayUnbilledCredit'] = $sum['unbilledCosts'];
            }
        }
        return $this;
    }

    /**
     * Calculates the benefits and costs for every single product based on $this->sum and stores them to
     * $this->product[$type][$name][externalCosts|...] = X
     *
     * @return $this
     * @throws \Exception
     */
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
                $p['count'] = 0;

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
                $p['bestPrice'] = 0;

                if ($sum['countBilled'] > 0)
                {
                    $p['turnoverRegular'] =         $sum['turnoverRegular']          / $sum['countBilled'];
                    $p['turnoverBilled'] =          $sum['turnoverBilled']           / $sum['countBilled'];
                    $p['externalCosts'] =           $sum['externalCostsBilled']      / $sum['countBilled'];
                    $p['workingTime'] =             $sum['workingTimeBilled']        / $sum['countBilled'];
                    $p['workingTimeCosts'] =        $sum['workingTimeCostsBilled']   / $sum['countBilled'];
                    $p['overheadCosts'] =           $sum['overheadCostsBilled']      / $sum['countBilled'];
                    $p['unbilledCostsShare'] =      $sum['unbilledCostsShare']       / $sum['countBilled'];

                    $p['cm1'] =                     $sum['cm1']                      / $sum['countBilled'];
                    $p['cm2'] =                     $sum['cm2']                      / $sum['countBilled'];
                    $p['cm3'] =                     $sum['cm3']                      / $sum['countBilled'];
                    
                    $p['bestPrice'] = ($sum['externalCostsBilled']+$sum['workingTimeCostsBilled']+$sum['overheadCostsBilled']+$sum['unbilledCostsShare'])/$sum['countBilled']*1.15; // Every programmer loves magic numbers
                }
                else
                {
                    $p['externalCosts'] =           $sum['externalCostsUnbilled']    / $sum['countUnbilled'];
                    $p['workingTime'] =             $sum['workingTimeUnbilled']      / $sum['countUnbilled'];
                    $p['workingTimeCosts']=         $sum['workingTimeCostsUnbilled'] / $sum['countUnbilled'];
                    $p['overheadCosts'] =           $sum['overheadCostsUnbilled']    / $sum['countUnbilled'];
                    $p['unbilledCosts'] =           $sum['unbilledCosts']            / $sum['countUnbilled'];

                    $p['cm1'] =                     $sum['cm1']                      / $sum['countUnbilled'];
                    $p['cm2'] =                     $sum['cm2']                      / $sum['countUnbilled'];
                    $p['cm3'] =                     $sum['cm3']                      / $sum['countUnbilled'];
                    
                    $p['bestPrice'] = ($sum['externalCostsUnbilled']+$sum['workingTimeCostsUnbilled']+$sum['overheadCostsUnbilled'])/$sum['countUnbilled']*1.15;
                }

                foreach ($sum['orders'] as $ordnr => $count)
                {
                    $p['orders'] .= "$ordnr({$count}x), ";
                }
            }
        }
        return $this;
    }

    /**
     * Calc all total-sums for each category
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
        // Adding percent-values isn't so smart
        $this->total['margin'] = $sum['margin'] = 100/$this->total['turnoverRegular']*$this->total['cm3'];
        return $this;
    }


    /**
     * Run all calculations in the right order:
     * - calcOverall()
     * - calcTotal()
     * - calcOverallOverhead()
     * - calcTotal()
     * - calcOverallContributionMargin()
     * - calcTotal()
     * - calcProducts()
     * - calcTotal
     *
     * Well, maybe you could optimize that, but I'm afraid u wouldn't get a relevant performance-enhancement
     *
     * @return $this
     * @throws \Exception
     */
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
     * Returns a german export-csv
     *
     * @return string $csv
     */
    public function getCsvGe()
    {
        $csv =
            'Produkttyp;'.
            'Produktname;'.
            'Anzahl;'.
            ';'.

            '�-Listenpreis (Stk./Mon.);'.
            ' - Fremdkosten;'.
            'db1;'.
            ';'.

            ' - Personalkosten (Mon.);'.
            '(Personalaufwand/Jahr);'.
            'db2;'.
            ';'.

            ' - Gemeinkosten (Srv.-Infrastruktur, ...);'.
            ' - Verrechnugsgemeinkosten (Inkl.-Domain, ...);'.
            'db3;'.
            'Marge (Listenpreis/db3);'.
            ';'.

            'x verkaufter Anzahl;'.
            ' - Rabatt;'.
            'DB4;'.
            ';'.

            ' - Verrechnungseinzelkosten;'.
            ' + Verrechnungseinzelgutschrift;'.
            'DB5;'.
            ';'.

            'Niedrigstpreis (Kosten+15% / Stk./Mon.); '.
            '�-Verkaufspreis (Stk./Mon.);'.
            'Umsatz (Gesamt/Mon.); '.
            ';'.

            'RP2-Auft�ge;'.
            "\n\n";

        foreach ($this->data['product'] as $type => &$products)
        {
            ksort($products);
            foreach ($products as $name => &$product)
            {
                // Lil alias
                $sum =& $this->sum[$type][$name];
                $total =& $this->total;

                $csv .= "$type;";
                $csv .= "$name;";
                $csv .= number_format($sum['count'], 0, ',', '.').';';
                $csv .= ';';

                $csv .= self::getEuroFormattedCsvColumn($product['turnoverRegular']);
                $csv .= self::getEuroFormattedCsvColumn(-$product['externalCosts']);
                $csv .= self::getEuroFormattedCsvColumn($product['cm1']);
                $csv .= ';';

                $csv .= self::getEuroFormattedCsvColumn(-$product['workingTimeCosts']);
                $csv .= date("H:i", mktime(0,0,0)+$product['workingTime']*60*60*12) .'; '; // mktime looks stupid here, but I needed hour 0
                $csv .= self::getEuroFormattedCsvColumn($product['cm2']);
                $csv .= ';';

                $csv .= self::getEuroFormattedCsvColumn(-$product['overheadCosts']);
                $csv .= self::getEuroFormattedCsvColumn(-$product['unbilledCostsShare']);
                $csv .= self::getEuroFormattedCsvColumn($product['cm3']);
                $csv .= number_format($sum['margin'], 0, ',', '.').' %;';
                $csv .= ';';

                $csv .= $sum['countBilled'].';';
                $csv .= self::getEuroFormattedCsvColumn(-$sum['discount']);
                $csv .= self::getEuroFormattedCsvColumn($sum['cm4']);
                $csv .= ';';

                $csv .= self::getEuroFormattedCsvColumn(-$sum['displayUnbilledCosts']);
                $csv .= self::getEuroFormattedCsvColumn($sum['displayUnbilledCredit']);
                $csv .= self::getEuroFormattedCsvColumn($sum['cm5']);
                $csv .= ';';


                $csv .= self::getEuroFormattedCsvColumn($product['bestPrice']);
                $csv .= self::getEuroFormattedCsvColumn($product['turnoverBilled']);
                $csv .= self::getEuroFormattedCsvColumn($sum['turnoverRegular']);
                $csv .= ';';

                $csv .= $product['orders'];
                $csv .= "\n";
            }
            $csv .= "\n";
        }
        
        $csv .=
            '**SUMME**;'.
            ';'.
            ';'.
            ';'.

            self::getEuroFormattedCsvColumn($total['turnoverRegular']).
            self::getEuroFormattedCsvColumn(-$total['externalCosts']).
            self::getEuroFormattedCsvColumn($total['cm1']).
            ';'.

            self::getEuroFormattedCsvColumn($total['workingTimeCosts']).
            //date("d \M\T, H \h / \M\o\\\\n.", mktime(0,0,0)+$total['workingTime']*60*60).';'.
            round($total['workingTime'],0).'h / Mon;'.
            self::getEuroFormattedCsvColumn($total['cm2']).
            ';'.

            self::getEuroFormattedCsvColumn(-$total['overheadCosts']).
            self::getEuroFormattedCsvColumn(-$total['unbilledCostsShare']).
            self::getEuroFormattedCsvColumn($total['cm3']).
            number_format($total['margin'], 0, ',', '.').' %;'.
            ';'.

            ';'.
            self::getEuroFormattedCsvColumn(-$total['discount']).
            self::getEuroFormattedCsvColumn($total['cm4']).
            ';'.

            ';'.
            ';'.
            self::getEuroFormattedCsvColumn($total['cm5']).
            ';'.


            ';'.
            self::getEuroFormattedCsvColumn($total['turnoverBilled']).
            self::getEuroFormattedCsvColumn($total['turnoverRegular']);

        $csv .= "\n\n\n\nGenerated with mgntRatioExport: https://github.com/ADoebeling/RP2-Toolbox \nCopyright (C) 2015 by Andreas D�beling <ad@1601.com> - 1601.production, Siegler&Th�mmler ohg, Erlangen";

        return $csv;
    }

    /**
     * Returns given $float as "1.234,56 �;"
     *
     * @param $float
     * @return string
     */
    public static function getEuroFormattedCsvColumn($float)
    {
        return number_format($float, 2, ',', '.').' �; ';
    }

    /**
     * Stores the calculated values to mysql
     *
     * @return $this
     * @throws \Exception
     * @todo Implement
     */
    public function store()
    {
        throw new \Exception("Sorry, not implemented yet", 501);
        return $this;
    }


    /**
     * Send application/csv-header and starts downloading the csv
     *
     * @param string $filename
     * @param string $source
     * @return $this
     * @throws \Exception
     */
    public function sendDownloadCsv($filename = NULL, $source = 'ge')
    {
        $filename = $filename !== NULL ? $filename : 'Hosting_MgntRatioExport_'.date('ymd').'_1SRV.csv';

        if ($source == 'ge')
        {
            $csv = $this->getCsvGe();
        }
        else
        {
            throw new \Exception("Sorry, not implemented yet", 501);
        }

        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=\"$filename\";");
        echo $csv;
        return $this;
    }
}