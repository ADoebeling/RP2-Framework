<?php
/**
 * Example for usage of \api\module\domainReadEntry
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link https://xing-ad.1601.com
 * @link https://www.1601.com
 */

/*
 * External Access
 */
require_once '../bootstrap.php';                // Include the framework
$rpf = new \rpf\system\rpf();                   // Instantiate the framework

$rpf
    ->getApi()                                  // Optional: Load the api
    ->getUser()                                 // Optional: Load the user-module
    ->httpAuth();                               // Optional: Send http-auth if you need to authenticate first

$result = $rpf                                  // Store result (of get() ) to the array $result

    ->getApi()                                  // Load the API
    ->getDomainReadEntry()                      // Load the API-module domainReadEntry (bbDomain::readEntry)

    ->setDomainId($dn)                          // Optional: Get a single domain by id
    ->setOeid($oeid)                            // Optional: Get domains by hidden rp2-order-id

    ->addSubdomain()                            // Optional: Add subdomains to result
    ->addMajor()                                // Optional: Add ???
    ->addFrontpage()                            // Optional: Add frontpage-settings
    ->addHandles()                              // Optional: Add domain-handle-information
    ->addLimits()                               // Optional: Add Limits (??? config or usage)
    ->addNameserver()                           // Optional: Add dns-records
    ->addPhpini()                               // Optional: Add php-ini-config
    ->addResellerFields()                       // Optional: Add order-name, ???
    ->addSpf()                                  // Optional: Add dns-spf-settings (???)
    ->addWebalizerSettings()                    // Optional: Add webalizier-settings
    ->addStaid()                                // Optional: Add ???

    ->getArray();                                    // Return result as array, primary-key set to domain

print_r($result);                               // Print-out the api-response