<?php
/**
 * invoiceTextExport
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @license cc-by-sa - http://creativecommons.org/licenses/by-sa/4.0/
 * @link http://xing.doebeling.de
 * @link https://github.com/ADoebeling
 * @version 0.1.151208_dev_1ad
 */

require_once '../class/extension/extension.php';
use www1601com\df_rp\extension\invoiceTextExport;

$e = new \www1601com\df_rp\extension\extension();
$e -> httpAuth();

$ajax = isset($_REQUEST['ajax']) ? true : flase;
$method = isset($_REQUEST['method']) ? (string) $_REQUEST['method'] : flase;
$ordNr = isset($_REQUEST['ordNr']) ? urldecode($_REQUEST['ordNr']) : flase;

/*
 * Ajax-Box: View single order / invoice
 */
if ($ajax && $method == 'getInvoiceBoxHtml' && $ordNr)
{
    // Invoice-Address
    $text .= $e->invoiceTextExport->getAddress($ordNr)['invoiceAddressBlock'];
    $text .= "<br>\n<br>\n";

    // Tariff
    $row = $e->invoiceTextExport->getTariff($ordNr);
    $text .= "<b>{$row['name']}</b> ({$row['priceFormatted']})<br>\n{$row['desc']}<br>\n<br>\n";

    // Domains
    $priceSum = 0;
    $priceDefaultSum = 0;
    foreach ($e->invoiceTextExport->getDomains($ordNr) as $row)
    {
        $price = invoiceTextExport::getPriceFormatted($row['price'], $row['priceDefault']);
        $priceSum += $row['price'];
        $priceDefaultSum += $row['priceDefault'];
        $domains .= "- {$row['domain']} ($price)<br>\n";
    }
    $amount = count($e->invoiceTextExport->getDomains($ordNr));
    $price = invoiceTextExport::getPriceFormatted($priceSum, $priceDefaultSum);
    $text .= "$amount Domains ($price)<br>\n$domains";
    $text .= "<br>\n<br>\n";

    // SSL-Certificates
    foreach ($e->invoiceTextExport->getCertificates($ordNr) as $type)
    {
        $priceSum = 0;
        $priceDefaultSum = 0;
        foreach ($type['items'] as $title => $row)
        {
            $price = invoiceTextExport::getPriceFormatted($row['price'], $row['priceDefault']);
            $priceSum += $row['price'];
            $priceDefaultSum += $row['priceDefault'];
            $items .= "- {$row['name']} ($price)<br>\n";
        }
        $amount = count($type['items']);
        $price = invoiceTextExport::getPriceFormatted($priceSum, $priceDefaultSum);
        $text .= "<b>{$type['title']}</b> ($price)<br>{$type['desc']}<br>\n<br>\n$items<br>\n";
    }

    // AddOns
    foreach ($e->invoiceTextExport->getAddOns($ordNr) as $row)
    {
        $price = invoiceTextExport::getPriceFormatted($row['price'], $row['priceDefault']);
        $text .= "<b>{$row['title']}</b> ($price)<br>\n{$row['desc']}<br>\n<br>\n";
    }

    // Exchange-Accounts
    foreach ($e->invoiceTextExport->getExchangeAccounts($ordNr) as $type)
    {
        $priceSum = 0;
        $priceDefaultSum = 0;
        foreach ($type['items'] as $title => $row)
        {
            $price = invoiceTextExport::getPriceFormatted($row['price'], $row['priceDefault']);
            $priceSum += $row['price'];
            $priceDefaultSum += $row['priceDefault'];
            $items .= "- {$row['name']} ($price)<br>\n";
        }
        $amount = count($type['items']);
        $price = invoiceTextExport::getPriceFormatted($priceSum, $priceDefaultSum);
        $text .= "<b>{$type['title']}</b> ($price)<br>{$type['desc']}<br>\n<br>\n$items<br>\n";
    }
    echo $text;
}

/*
 * List of all Orders
 */
else
{

    foreach ($e->invoiceTextExport->getAllOrders() as $ordNrLink => $row)
    {
        $ordNrLink = urlencode($ordNr);
        echo <<<END
        <li class="row">
            <span class="customerDisplayName">{$row['customerDisplayName']}</span>
            <span class="ordNr">$ordNr</span>
            <span class="turnoverMonth">{$row['turnoverMonth']}</span>
            <span class="turnoverYear">{$row['turnoverMonth']}</span>
            <a href="?ajax&method=getInvoiceBoxHtml&ordNr=$ordNrLink" class="fancybox.ajax"><span class="menu"></span></a>
        </li>
END;
    }

}