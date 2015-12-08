<?php namespace www1601com\df_rp\extension;

require_once __DIR__.'/../extensionModule.php';

/**
 * Class invoiceTextExport
 *
 * This class provides methodes to list all orders, each with customer-name, turnover per month and turnover per year.
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
 * @package www1601com\df_rp\extension
 */
class invoiceTextExport extends extensionModule
{
    /**
     * @var array format-pattern
     */
    private static $format = ['zeroString' => 'Inklusive', 'discountPattern' => '$priceDefault // Abzgl. $discount Rabatt'];

    public function __construct(extension &$system)
    {
        parent::__construct($system);
    }

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
        $orders = $this->system->orders->loadAll('accounting')->getOrders();


        return array();
    }

    /**
     * Returns the Invoice-Address as formatted string.
     *
     * @param string $ordNr
     * @return array
     */
    public function getAddress($ordNr)
    {
        $return['invoiceAddressBlock'] = NULL;
        return $return;
    }

    /**
     * Returns the ordered tariff
     * (Doesn't takes care of validity period)
     *
     * @param string $ordNr
     * @return array [name, desc, priceFormatted]
     */
    public function getTariff($ordNr)
    {
        return (array) $return;
    }

    /**
     * Returns the ordered domains
     * (Doesn't takes care of validity period)
     *
     * @param string $ordNr
     * @return array [ amount, priceFormatted, item[ name, priceFormatted ] ]
     */
    public function getDomains($ordNr)
    {
        return (array) $return;
    }


    /**
     * Returns the ordered AddOns
     * (Doesn't takes care of validity period)
     *
     * @param string $ordNr
     * @return array [ item[ name, amount, desc, priceFormatted ] ]
     */
    public function getAddOns($ordNr)
    {
        return (string) $return;
    }

    /**
     * Returns the ordered certificates
     * (Doesn't takes care of validity period)
     *
     * @param string $ordNr
     * @return array [ name, amount, desc, priceFormatted, item[ name, priceFormatted ] ]
     */
    public function getCertificates($ordNr)
    {
        return (string) $return;
    }


    /**
     * Returns the ordered exchange-accounts
     * (Doesn't takes care of evaluation period)
     *
     * @param string $ordNr
     * @return array [ name, amount, desc, priceFormatted, item[ name, priceFormatted ] ]
     */
    public function getExchangeAccounts($ordNr)
    {
        return (string) $return;
    }


    /**
     * Formats float into 12.345,67 € and a integer into 12.345,- €
     * Returns $zeroString if price is NULL
     *
     * @param float $price
     * @param string self
     * @return string (12.345,67 €|12.345,- €|Inklusive)
     */
    static function getEuroFormated($price, $zeroString = NULL)
    {
        $zeroString = $zeroString != NULL ?: self::$format['zeroString'];
        return round($price,2) > 0 ? str_replace(',00 ', ',- ', number_format($price, 2, ',', '.')).' €' : $zeroString;
    }

    /**
     * Formats $price and $priceDefault as invoice-string
     *
     * @param float $price
     * @param float $priceDefault
     * @param string $patternZero
     * @param string $patternDiscount
     * @return string (12.345,67 €|12.345,- €|12.345,67 € // Abzgl. 1.345,- € Rabatt)
     */
    static function getPriceFormatted($price, $priceDefault = NULL, $patternZero = NULL, $patternDiscount = NULL)
    {
        $patternDiscount = $patternDiscount != NULL ?: self::$format['discountPattern'];
        if (round($price,2) >= round($priceDefault,2))
        {
            return self::getEuroFormated($price, $patternZero);
        }

        else
        {
            $price = self::getEuroFormated($price, $patternZero);
            $discount = self::getEuroFormated($priceDefault-$price, $patternZero);
            return eval($patternDiscount);
        }
    }

}