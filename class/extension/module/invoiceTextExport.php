<?php

namespace rpf\extension\module;
use rpf\extension\extensionModule;


/**
 * Class invoiceTextExport
 *
 * This class provides methods to list all orders, each with customer-name, turnover per month and turnover per year.
 * Additionally it's able to show the invoice-address, the tariff and all positions, each with turnover per month.
 *
 * It is build to provide a copy-and-paste interface for all dispositions to bill the rp2-data in an external
 * program.
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling
 * @link http://xing.doebeling.de
 * @link https://www.1601.com
 *
 * @TODO Use bbOrder::readDisposition instead of readEntry to get so_type and clean prices #5401975
 */
class invoiceTextExport extends extensionModule
{
    /**
     * @var array format-pattern
     */
    private static $format = ['zeroString' => 'Inklusive', 'discountPattern' => '$priceDefault // Abzgl. $discount Rabatt'];

    /**
     * Set format-pattern
     *
     * @param string $name
     * @param string $value
     */
    public function setFormat ($name, $value)
    {
        $this->format[$name] = $value;
    }


    /**
     * Returns all orders
     *
     * @return array [ $ordNr[ customerDisplayName, priceMonth, priceYear)
     */
    public function getAllOrders()
    {
        //$result = $this->getApi()->getOrder()->loadAll(['accounting' => 1])->getData();
        $result = $this->getApi()->getOrder()->loadAll(['accounting' => 1])->getData();
        //$result = $this->getApi()->getOrderReadEntry()->addAccountEntries();

        foreach ($result as $row)
        {
            $priceMonth = 0;
            foreach ($row['dispositions'] as $dispo)
            {
                // Workaround against RP2-API-Bug #5402189:
                // unit_net is is calculated wrong: round($rp2UserInputPrice*1.19, 2)/1.19
                $priceMonth += round($dispo['price']['unit_net'],2) * $dispo['amount'];
            }
            if ($priceMonth > 0)
            {

                $return[$row['ordnr']]['customerDisplayName'] = isset($row['cus_company']) && !empty($row['cus_company']) ? "{$row['cus_company']}<br>({$row['cus_last_name']}, {$row['cus_first_name']})" : "{$row['cus_last_name']}, {$row['cus_first_name']}";
                $return[$row['ordnr']]['priceYear'] = self::getPriceFormatted($priceMonth * 12);
                $return[$row['ordnr']]['priceMonth'] = self::getPriceFormatted($priceMonth);
            }
        }
        return $return;
    }

    /**
     * Returns the Invoice-Address as formatted string.
     *
     * @param string $ordNr
     * @return array
     */
    public function getAddress($ordNr)
    {
        $address = $this->getApi()->getOrder()->load($ordNr)->getData()[$ordNr]['customer']['adress']['inv'];
        $return['invoiceAddressBlock'] = !empty($address['company']) ?  $address['company']."\n" : '';
        $return['invoiceAddressBlock'] .= "{$address['first_name']} {$address['last_name']}\n";
        $return['invoiceAddressBlock'] .= !empty($address['adress_1']) ?  $address['adress_1']."\n" : '';
        $return['invoiceAddressBlock'] .= !empty($address['adress_2']) ?  $address['adress_2']."\n" : '';
        $return['invoiceAddressBlock'] .= !empty($address['zip']) ?  $address['zip']." " : '';
        $return['invoiceAddressBlock'] .= !empty($address['city']) ?  $address['city']."\n" : '';
        return $return;
    }

    /**
     * Returns the ordered tariff
     * (Doesn't takes care of validity period)
     *
     * @param string $ordNr
     * @return array [name, desc, priceFormatted]
     * @throws \Exception
     */
    public function getTariff($ordNr)
    {
        $dispos = $this->getApi()->getOrder()->load($ordNr)->getData()[$ordNr]['dispositions'];
        foreach ($dispos as $row)
        {
            if ($row['product']['norm'] == 'tariff')
            {
                $return['name'] = $row['product']['name'];
                $return['desc'] = $row['product']['descr'];
                $return['priceFormatted'] = self::getPriceFormatted($row['price']['unit_net'], $row['price']['default_net']);
                return (array) $return;
            }
        }
        throw new \Exception ("OrderNr $ordNr doesn't exists or doesn't has a tariff", 404);
    }

    /**
     * Returns the ordered domains
     * (Doesn't takes care of validity period)
     *
     * @param string $ordNr
     * @return array [ title, amount, priceFormatted, item[ name, priceFormatted ] ]
     */
    public function getDomains($ordNr)
    {
        $dispos = $this->getApi()->getOrder()->load($ordNr)->getData()[$ordNr]['dispositions'];
        $sumPrice = 0;
        $sumPriceDefault = 0;
        $return['item'] = array();

        foreach ($dispos as $row)
        {
            if ($row['product']['norm'] == 'domain')
            {
                // Workaround against RP2-API-Bug #5401975
                // $row['product']['name'] is empty:
                // $return['name'] = $row['product']['name'];
                $item['name'] = $row['descr'];
                $item['priceFormatted'] = self::getPriceFormatted($row['price']['unit_net'], $row['price']['default_net']);
                $sumPrice += round($row['price']['unit_net'], 2);
                $sumPriceDefault += round($row['price']['default_net'],2);

                if ($row['price']['unit_net'] == 0)
                {
                    $freeOnTop[$row['descr']] = $item;
                }
                else
                {
                    $return['item'][$row['descr']] = $item;
                }
            }
        }
        if (!empty($return['item']))
        {
            asort($return['item']);
        }

        if (!empty($freeOnTop))
        {
            asort($freeOnTop);
            $return['item'] = array_merge($freeOnTop, $return['item']);
        }

        $return['amount'] = count($return['item']);
        $return['priceFormatted'] = self::getPriceFormatted($sumPrice, $sumPriceDefault);

        if ($return['amount'] == 0)         $return['title'] = "Keine Domains reserviert";
        else if ($return['amount'] == 1)    $return['title'] = "Domain";
        else                                $return['title'] = "{$return['amount']} Domains";

        return (array) $return;
    }


    /**
     * Returns the ordered AddOns
     * (Doesn't takes care of validity period)
     *
     * @param string $ordNr
     * @return array [ $pronr[ title, name, amount, desc, priceFormatted ] ]
     */
    public function getAddOns($ordNr)
    {
        $dispos = $this->getApi()->getOrder()->load($ordNr)->getData()[$ordNr]['dispositions'];
        $sumPrice = 0;
        $sumPriceDefault = 0;
        $return['item'] = array();

        foreach ($dispos as $row) {
            if ($row['product']['norm'] == 'add-on') {
                $item['name'] = !empty($row['descr']) ? $row['descr'] : $row['product']['name'];
                $item['desc'] = $row['product']['descr'];
                $item['amount'] = $row['amount'];
                $item['title'] = $item['amount'] > 1 ? "{$row['amount']}x {$item['name']}" : $item['name'];

                // Workaround against RP2-API-Bug #5402189:
                // unit_net is is calculated wrong: round($rp2UserInputPrice*1.19, 2)/1.19
                $item['priceFormatted'] = self::getPriceFormatted($row['price']['unit_net'] * $row['amount'], $row['price']['default_net'] * $row['amount'], $row['can_account']);
                $sumPrice += $row['price']['unit_net'] * $row['amount'];
                $sumPriceDefault += $row['price']['default_net'] * $row['amount'];

                // We need the desc as key for correct sorting AND
                // need the disposition-id to prohibit overwritten positions
                // by multiple added positions.
                $return['item'][$row['product']['pronr'].$row['odid']] = $item;
            }
        }
        asort($return['item']);
        return (array) $return;
    }

    /**
     * Returns the ordered certificates
     * (Doesn't takes care of validity period)
     *
     * @param string $ordNr
     * @return array [ $type[ $domain[ title, amount, desc, priceFormatted, item[ name, priceFormatted ] ] ] ]
     */
    public function getCertificates($ordNr)
    {
        $disposition = $this->getApi()->getOrder()->loadDisposition($ordNr)->getDisposition($ordNr);
        $disposition = $this->getApi()->getOrder()->loadDisposition($ordNr)->getDisposition($ordNr);


        $sumPrice = 0;
        $sumPriceDefault = 0;
        $return = array();

        foreach ($disposition as $row)
        {
            // Workaround against RP2-API-BUG #5402091:
            // There is no [product][norm] for ssl-certificates
            // U need to rename all ssl-certificates to SSL_foobar to get grabbed
            // here correctly
            // => readDispositions()
            if ($row['product']['norm'] == 'ext' && strpos($row['product']['pronr'], 'SSL_') === 0)
            {
                // Workaround against RP2-API-Bug #5402235:
                // There is no link between a ssl-certificate and the
                // Domain it is registered for
                // The only known way is to parse the domain out of the auto-generated desc
                $regEx = "/(\\S*\\.\\S*\\.\\S*)/"; // quick and dirty, i Know
                preg_match($regEx, $row['descr'], $domain);
                $domain = $domain[1];

                $return[$row['product']['pronr']]['item'][$domain]['name'] = $domain;

                // Workaround against RP2-API-Bug #5402189:
                // unit_net is is calculated wrong: round($rp2UserInputPrice*1.19, 2)/1.19
                $return[$row['product']['pronr']]['item'][$domain]['priceFormatted'] = self::getPriceFormatted($row['price']['unit_net'], $row['price']['default_net']);

                $tmp[$row['product']['pronr']]['sumPrice'] += $row['price']['unit_net'];
                $tmp[$row['product']['pronr']]['sumPriceDefault'] += $row['price']['default_net'];
                $tmp[$row['product']['pronr']]['amount'] = count($return[$row['product']['pronr']]['item']);

                $return[$row['product']['pronr']]['title'] = $tmp[$row['product']['pronr']]['amount'] > 1 ? "{$tmp[$row['product']['pronr']]['amount']}x {$row['product']['name']}" : $row['product']['name'];
                $return[$row['product']['pronr']]['desc'] = $row['product']['descr'];
                $return[$row['product']['pronr']]['priceFormatted'] = self::getPriceFormatted($tmp[$row['product']['pronr']]['sumPrice'], $tmp[$row['product']['pronr']]['sumPriceDefault']);

                asort($return[$row['product']['pronr']]['item']);
            }
        }


        asort($return);
        return (array) $return;
    }


    /**
     * Returns the ordered exchange-accounts
     * (Doesn't takes care of evaluation/validity period)
     *
     * @param string $ordNr
     * @return array [ type[ name, amount, desc, priceFormatted, item[ name, priceFormatted ] ] ]
     */
    public function getExchangeAccounts($ordNr)
    {
        $dispos = $this->getApi()->getOrder()->load($ordNr)->getData()[$ordNr]['dispositions'];

        $sumPrice = 0;
        $sumPriceDefault = 0;
        $return = array();

        foreach ($dispos as $row)
        {

            // Workaround against RP2-API-BUG #5402091:
            // There is no [product][norm] for exchange-accounts
            // U need to rename all exchange-accounts to EXCHANGE_foobar to get grabbed
            // here correctly
            if ($row['product']['norm'] == 'ext' && strpos($row['product']['pronr'], 'EXCHANGE_') === 0) //
            {
                $pk = $row['descr'];

                $return[$row['product']['pronr']]['item'][$pk]['name'] = $pk;

                // Workaround against RP2-API-Bug #5402189:
                // unit_net is is calculated wrong: round($rp2UserInputPrice*1.19, 2)/1.19
                $return[$row['product']['pronr']]['item'][$pk]['priceFormatted'] = self::getPriceFormatted($row['price']['unit_net'], $row['price']['default_net']);

                $tmp[$row['product']['pronr']]['sumPrice'] += $row['price']['unit_net'];
                $tmp[$row['product']['pronr']]['sumPriceDefault'] += $row['price']['default_net'];
                $tmp[$row['product']['pronr']]['amount'] = count($return[$row['product']['pronr']]['item']);

                $return[$row['product']['pronr']]['title'] = $tmp[$row['product']['pronr']]['amount'] > 1 ? "{$tmp[$row['product']['pronr']]['amount']}x {$row['product']['name']}" : $row['product']['name'];
                $return[$row['product']['pronr']]['desc'] = $row['product']['descr'];
                $return[$row['product']['pronr']]['priceFormatted'] = self::getPriceFormatted($tmp[$row['product']['pronr']]['sumPrice'], $tmp[$row['product']['pronr']]['sumPriceDefault']);

                asort($return[$row['product']['pronr']]['item']);
            }
        }
        asort($return);
        return (array) $return;
    }


    /**
     * Formats float into 12.345,67 � and integer into 12.345,- �
     * Returns $zeroString if price is NULL
     *
     * @param float|int $price
     * @param string $zeroString = Inklusive
     * @return string (12.345,67 �|12.345,- �|Inklusive)
     */
    static function getEuroFormatted($price, $zeroString = 'Inklusive')
    {
        $price = round($price, 2);
        return $price > 0 ? str_replace(',00', ',-', number_format($price, 2, ',', '.')).' &euro;' : $zeroString;
    }

    /**
     * Formats $price and $priceDefault as invoice-string
     *
     * @param float $price
     * @param float $priceDefault
     * @param string $patternZero
     * @param string $patternDiscount
     * @param bool $activeAccounting
     * @return string (12.345,67 �|12.345,- �|12.345,67 � // Abzgl. 1.345,- � Rabatt)
     */
    static function getPriceFormatted($price, $priceDefault = NULL, $activeAccounting = 1)
    {
        $suffix = $activeAccounting == 0 ? ' !!INAKTIV!!' : '';

        if (round($price,2) >= round($priceDefault,2))
        {
            return self::getEuroFormatted($price, "Inklusive$suffix");
        }
        else
        {
            $percent = 100 - 100 / round($priceDefault,2) * round($price,2);
            if ($percent == 100 | $percent == 75 | $percent == 50 | $percent == 25 | $percent == 20 | $percent == 10)
            {
                $discount = "$percent%";
                //$discount = self::getEuroFormatted($priceDefault-$price);
            }
            else
            {
                $discount = self::getEuroFormatted($priceDefault-$price);
            }
            $priceDefault = self::getEuroFormatted($priceDefault);
            return "$priceDefault | Abzgl. $discount Rabatt$suffix";
        }
    }
}