<?php
/**
 * Example for CustomerReadDiscount()
 * @author Lukas M. Beck
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 */

/*
* External Access
*/
require_once '../../bootstrap.php';

$rpf = new \rpf\system\rpf();                   // Instantiate the framework
$rpf
    ->getApi()                                  // Optional: Load the api
    ->getUser()                                 // Optional: Load the user-module
    ->httpAuth();                               // Optional: Send http-auth if you need to authenticate first

$result = $rpf
    ->getApi()                                  // Load the API
    ->getCustomerReadDiscount()                 // Load the API-module customerReadDiscount (bbCustomer::readDiscount)

    ->setCustomerDiscount()

    ->getArray();

var_dump($result);