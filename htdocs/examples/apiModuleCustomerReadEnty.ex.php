<?php
/**
 * Example for CustomerReadEntry()
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
    ->getCustomerReadEntry()                    // Load the API-module customerReadEntry (bbCustomer::readEntry)

    ->setCustomerId($cId)                       // Set filter on customer-id
    ->setUserId($uId)                           // Set filter on user-is
    ->setNewsletter()                           // Set filter on newsletter

    ->addReturnAddress()                        // Return the address
    ->addReturnAddressCountry()                 // Return address with country-settings
    ->addReturnOrders()                         // Return orders
    ->addReturnAccountEntries()
    ->addReturnSettlements()
    ->addReturnLimits()
    ->addReturnTariff()
    ->addReturnUser()
    ->addReturnPolicy()
    ->addReturnHandles()
    ->addReturnStaid()
    ->addReturnOverview()
    ->addReturnPayInfo()
    ->addReturnPayInfoBank()
    ->addReturnPayment()

    ->addReturnPhoneEncodet()

    ->getArray();

var_dump($result);
