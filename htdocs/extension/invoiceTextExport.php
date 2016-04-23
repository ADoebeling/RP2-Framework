<?php
/**
 * invoiceTextExport
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @license cc-by-sa - http://creativecommons.org/licenses/by-sa/4.0/
 * @link http://xing.doebeling.de
 * @link https://github.com/ADoebeling
 */


require_once __DIR__ . '/../../bootstrap.php';

$rpf = new \rpf\system\rpf();

$rpf
    ->getApi()
    ->getUser()
    ->httpAuth();


$ajax = isset($_REQUEST['ajax']) ? true : false;
$method = isset($_REQUEST['method']) ? (string) $_REQUEST['method'] : false;
$ordNr = isset($_REQUEST['ordNr']) ? urldecode($_REQUEST['ordNr']) : false;

/*
 * Ajax-Box: View single order / invoice
 */
if ($ajax && $method == 'getInvoiceBoxHtml' && $ordNr)
{
    // Invoice-Address
    $text .= "<h3>Offer/Invoice for $ordNr</h3>";
    $text .= "<textarea readonly>";
    $text .= $rpf->getExtension()->getInvoiceTextExport()->getAddress($ordNr)['invoiceAddressBlock'];
    $text .= "</textarea>\n\n";
    $text .= "<br>\n";

    // Tariff
    $data = $rpf->getExtension()->getInvoiceTextExport()->getTariff($ordNr);
    $text .= "<article class=\"2column\">";
    $text .= "<h3>{$data['name']} <span class=\"price\">({$data['priceFormatted']})</span></h3>\n";
    $text .= "<textarea readonly>";
    $text .= "{$data['desc']}";
    $text .= "</textarea>\n\n";
    $text .= "</article>";


    // Domains
    $data = $rpf->getExtension()->getInvoiceTextExport()->getDomains($ordNr);
    $text .= "<article class=\"2column\">";
    $text .= "<h3>{$data['title']} <span class=\"price\">({$data['priceFormatted']})</span></h3>\n";
    $text .= "<textarea readonly>";
    foreach ($data['item'] as $row)
    {
        $text .= "- {$row['name']} ({$row['priceFormatted']})\n";
    }
    $text .= "</textarea>";
    $text .= "<br>\n";
    $text .= "</article>";

    // SSL-Certificates
    $data = $rpf->getExtension()->getInvoiceTextExport()->getCertificates($ordNr);

    foreach ($data as $type)
    {
        $text .= "<article class=\"2column\">";
        $text .= "<h3>{$type['title']} <span class=\"price\">({$type['priceFormatted']})</span></h3>\n";
        $text .= "<textarea readonly>";
        $text .= "{$type['desc']}\n\n";
        foreach ($type['item'] as $row)
        {
            $text .= "- {$row['name']} ({$row['priceFormatted']})\n";
        }
        $text .= "</textarea>";
        $text .= "</article>";
    }
    $text .= "<br>\n";


    // Exchange-Accounts
    $data = $rpf->getExtension()->getInvoiceTextExport()->getExchangeAccounts($ordNr);

    foreach ($data as $type)
    {
        $text .= "<article class=\"2column\">";
        $text .= "<h3>{$type['title']} <span class=\"price\">({$type['priceFormatted']})</span></h3>\n";
        $text .= "<textarea>";
        $text .= "{$type['desc']}\n\n";
        foreach ($type['item'] as $row)
        {
            $text .= "- {$row['name']} ({$row['priceFormatted']})\n";
        }
        $text .= "</textarea>";
        $text .= "</article>";
    }
    $text .= "<br>\n";


    // AddOns
    foreach ($rpf->getExtension()->getInvoiceTextExport()->getAddOns($ordNr)['item'] as $row)
    {
        $text .= "<article class=\"2column\">";
        $text .= "<h3>{$row['title']}<span class=\"price\">({$row['priceFormatted']})</span></h3>\n";
        $text .= "<textarea readonly>";
        $text .= "{$row['desc']}";
        $text .= "</textarea>";
        $text .= "</article>";
    }
    echo $text;
}

/*
 * List of all Orders
 */
else
{
    echo "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"UTF-8\">
    <title>RP²-Extension: InvoiceTextExport</title>
    <meta name=\"author\" content=\"Andreas Doebeling\">
    <style>
        body
        {
            font-family: Arial;
            font-size: 0.8em;
        }

        a
        {
            color: deeppink;
            text-decoration: none;
        }

        a:hover
        {
            font-weight: bold;
        }

        h3
        {
        border-bottom: 2px solid grey;
        margin-bottom: 0;
        }

        h3 .price
        {
            font-weight: normal;
            display: block;
            float:right;
        }

        li
        {
            border-bottom: 1px solid grey;
            list-style: none;
        }

        li:hover
        {
            background-color: #bfbfbf;
        }

        li a
        {
            display: inline-block;
            height: 100%;
        }

        li span
        {
            padding: 0.4em;
            vertical-align: middle;
            display: inline-block;
            height: 100%;
        }

        .customerDisplayName, .ordNr
        {
            width: 16em;
            overflow: hidden;
        }

        .priceMonth, .priceYear
        {
            width: 8em;
        }

        article
        {
            /*width: 300px;
            float: left;
            padding-right: 20px;
            display: block;*/
            width: 100%;
        }

        textarea
        {
            width: 100%;
            border: 0;
            height: 6em;
            font-family: Arial;
            margin: 0;
            padding: 0;
        }
        textarea:active, textarea:focus
        {
            background-color: grey;
        }

        .featherlight .featherlight-content
        {
            max-height: 90% !important;
            width: 95% !important;
        }
    </style>
    <link href=\"extension/static/js/featherlight-1.3.4/release/featherlight.min.css\" type=\"text/css\" rel=\"stylesheet\">

</head>

<body>
    <script src=\"extension/static/js/jquery-latest.min.js\"></script>
    <script src=\"extension/static/js/featherlight-1.3.4/release/featherlight.min.js\" type=\"text/javascript\" charset=\"utf-8\"></script>

";
    $lastCustomer = NULL;
    foreach ($rpf->getExtension()->getInvoiceTextExport()->getAllOrders() as $ordNr => $row)
    {
        $customerDisplayName = $row['customerDisplayName'] == $lastCustomer ? '': $row['customerDisplayName'];
        $lastCustomer = $row['customerDisplayName'];
        $ordNrLink = urlencode($ordNr);

        echo <<<END
        <li class="row">
            <span class="customerDisplayName">$customerDisplayName</span>
            <span class="ordNr">$ordNr</span>
            <a class="fancybox.ajax" href="#invoiceTextExport.php" data-featherlight="?ajax&method=getInvoiceBoxHtml&ordNr=$ordNrLink">
                <span class="priceMonth">{$row['priceMonth']} / Mon.</span>
                <span class="priceYear">{$row['priceYear']} / Year</span>
            </a>
        </li>
END;
    }
    echo <<<END
</body>
</html>
END;

}