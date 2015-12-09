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
    echo "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"UTF-8\">
    <title>RP²-Extension: InvoiceTextExport</title>
    <meta name=\"author\" content=\"Andreas Doebeling\">
    <!--<link href=\"style.css\" type=\"text/css\" rel=\"stylesheet\">-->
    <style>
        body
        {
            font-family: Arial;
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

        li span
        {
            padding: 5px 15px 5px 0;
            vertical-align: middle;
        }

        .customerDisplayName, .ordNr
        {
            width: 15em;
            height: 2em;
            display:inline-block;
            overflow: hidden;
        }

        .priceMonth, .priceYear
        {
            width: 4em;
        }
    </style>
</script>
</head>

<body>
    <!-- Add jQuery library -->
    <script type=\"text/javascript\" src=\"static/js/jquery-latest.min.js\"></script>

    <!-- Add mousewheel plugin (this is optional) -->
    <script type=\"text/javascript\" src=\"static/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js\"></script>

    <!-- Add fancyBox -->
    <link rel=\"stylesheet\" href=\"static/js/fancybox/source/jquery.fancybox.css?v=2.1.5\" type=\"text/css\" media=\"screen\" />
    <script type=\"text/javascript\" src=\"static/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5\"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <link rel=\"stylesheet\" href=\"static/js/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5\" type=\"text/css\" media=\"screen\" />
    <script type=\"text/javascript\" src=\"static/js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5\"></script>
    <script type=\"text/javascript\" src=\"static/js/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6\"></script>

    <link rel=\"stylesheet\" href=\"static/js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7\" type=\"text/css\" media=\"screen\" />
    <script type=\"text/javascript\" src=\"static/js/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7\"></script>

    <script type=\"text/javascript\">
        $(document).ready(function() {
            $(\".fancybox\").fancybox();
        });
    </script>
";
    $lastCustomer = NULL;
    foreach ($e->invoiceTextExport->getAllOrders() as $ordNr => $row)
    {
        $customerDisplayName = $row['customerDisplayName'] == $lastCustomer ? '': $row['customerDisplayName'];
        $lastCustomer = $row['customerDisplayName'];
        $ordNrLink = urlencode($ordNr);

        echo <<<END
        <li class="row">
            <span class="customerDisplayName">$customerDisplayName</span>
            <span class="ordNr">$ordNr</span>
            <a class="fancybox.ajax" href="invoiceTextExport.php?ajax&method=getInvoiceBoxHtml&ordNr=$ordNrLink">
                <span class="turnoverMonth">{$row['priceMonth']} / Mon.</span>
                <span class="turnoverYear">{$row['priceYear']} / Year</span>
            </a>
        </li>
END;
    }
    echo <<<END
</body>
</html>
END;

}