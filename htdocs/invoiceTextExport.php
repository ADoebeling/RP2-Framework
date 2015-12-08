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

$e = new \www1601com\df_rp\extension\extension();
$e -> httpAuth();

$ajax = isset($_REQUEST['ajax']) ? true : false;
$method = isset($_REQUEST['method']) ? (string) $_REQUEST['method'] : false;
$ordNr = isset($_REQUEST['ordNr']) ? urldecode($_REQUEST['ordNr']) : false;

/*
 * Ajax-Box: View single order / invoice
 */
if ($ajax && $method == 'getInvoiceBoxHtml' && $ordNr)
{
    // Invoice-Address
    $text .= $e->invoiceTextExport->getAddress($ordNr)['invoiceAddressBlock'];
    $text .= "<br>\n<br>\n";

    // Tariff
    $data = $e->invoiceTextExport->getTariff($ordNr);
    $text .= "<b>{$data['name']}</b> ({$data['priceFormatted']})<br>\n{$data['desc']}<br>\n<br>\n";

    // Domains
    $data = $e->invoiceTextExport->getDomains($ordNr);
    $text .= "{$data['title']} ({$data['priceFormatted']})<br>\n";
    foreach ($data['item'] as $row)
    {
        $text .= "- {$row['name']} ({$row['priceFormatted']})<br>\n";
    }
    $text .= "<br>\n";

    // SSL-Certificates
    $data = $e->invoiceTextExport->getCertificates($ordNr);
    foreach ($data as $type)
    {
        $text .= "{$type['title']} ({$type['priceFormatted']})<br>\n{$type['desc']}<br>\n<br>\n";
        foreach ($type['items'] as $row)
        {
            $text .= "- {$row['name']} ({$row['priceFormatted']})<br>\n";
        }
    }
    $text .= "<br>\n";

    // Exchange-Accounts
    $data = $e->invoiceTextExport->getExchangeAccounts($ordNr);
    foreach ($data as $type)
    {
        $text .= "{$type['title']} ({$type['priceFormatted']})<br>\n{$type['desc']}<br>\n<br>\n";
        foreach ($type['items'] as $row)
        {
            $text .= "- {$row['name']} ({$row['priceFormatted']})<br>\n";
        }
    }
    $text .= "<br>\n";

    // AddOns
    foreach ($e->invoiceTextExport->getAddOns($ordNr)['item'] as $row)
    {
        $text .= "<b>{$row['title']}</b> ({$row['priceFormatted']})<br>\n{$row['desc']}<br>\n<br>\n";
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