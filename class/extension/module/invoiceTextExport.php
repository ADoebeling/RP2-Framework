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
    public function __construct(extension &$system)
    {
        parent::__construct($system);
    }


    /**
     * Returns all orders
     *
     * @return array $orders[$ordNr] = array ('customerDisplayName', 'turnoverMonth', 'turnoverYear')
     */
    public function getAllOrders()
    {

        return array();
    }

    /**
     * Returns the Invoice-Address as formatted string.
     *
     * @param $ordNr
     * @return string
     */
    public function getInvoiceAddress($ordNr)
    {
        $address = '';
        return (string) $return;
    }

    /**
     * Returns name and description of the ordered tariff as formatted string
     *
     * @param $ordNr
     * @return string (<b>Tariff XY</b><br>Some lines description)
     */
    public function getTariff($ordNr)
    {
        return (string) $return;
    }

    /**
     * Returns name and price of the ordered domains as formatted string
     *
     * @param $ordNr
     * @return string (<b>XX Domains</b> (XX,- € // Abzgl. XX,- € Rabatt)</br> - domain.tld (1,00 € // Abzgl. 0,25 € Rabatt)<br>- domain2 ...)
     */
    public function getDomains($ordNr)
    {
        return (string) $return;
    }


    /**
     * Returns name and price of the ordered addons as formatted string
     *
     * @param $ordNr
     * @return string (<b>XX AddOn</b> (XX,- € // Abzgl. XX,- € Rabatt)</br>Some lines of description<br><br><b>XX AddOn</b>...)
     */
    public function getAddOns($ordNr)
    {
        return (string) $return;
    }

    /**
     * Returns name and price of the ordered ssl-certificates as formatted string
     *
     * @param $ordNr
     * @return string (<b>XX Zertifikat XY</b> (XX,- € // Abzgl. XX,- € Rabatt)</br> Some lines of description<br>- domain.tld (XX,- € // Abzgl. XX,- € Rabatt)<br> ...)
     */
    public function getCertificates($ordNr)
    {
        return (string) $return;
    }


    /**
     * Returns name and price of the ordered exchange-accounts as formatted string
     *
     * @param $ordNr
     * @return string (<b>XX Exchange-Account XY</b>(XX,- € // Abzgl. XX,- € Rabatt)<br>Some lines of description<br>- account@domain.tld (XX,- € // Abzgl. XX,- € Rabatt)<br> ...)
     */
    public function getExchangeAccounts($ordNr)
    {
        return (string) $return;
    }

}