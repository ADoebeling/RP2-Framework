<?php

$resellerDiscount = 0; // %
$domainDeDiscount = 0; // %

$c['domain']['DOM_DE']['externalCosts'] =          0.79*$domainDeDiscount/1.19;
$c['domain']['DOM_AT']['externalCosts'] =          1.49/1.19;
$c['domain']['DOM_BIZ']['externalCosts'] =         1.39*$resellerDiscount/1.19;
$c['domain']['DOM_CC']['externalCosts'] =          3.99*$resellerDiscount/1.19;
$c['domain']['COM_CO']['externalCosts'] =          1.39/1.19;
$c['domain']['DOM_COM']['externalCosts'] =         1.39/1.19;
$c['domain']['DOM_EU']['externalCosts'] =          0.99/1.19;
$c['domain']['DOM_INFO']['externalCosts'] =        1.39/1.19;
$c['domain']['DOM_ME']['externalCosts'] =          3.49/1.19;
$c['domain']['DOM_MOBI']['externalCosts'] =        2.49/1.19;
$c['domain']['DOM_NAME']['externalCosts'] =        1.49/1.19;
$c['domain']['DOM_NET']['externalCosts'] =         1.39/1.19;
$c['domain']['DOM_ORG']['externalCosts'] =         1.39/1.19;
$c['domain']['DOM_TV']['externalCosts'] =          4.99/1.19;
$c['domain']['DOM_WS']['externalCosts'] =          2.49/1.19;

$c['domain']['DOM_CHEAP']['externalCosts'] =       3.99*$resellerDiscount/1.19;

