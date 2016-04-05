<?php
/**
 * This example shows how to work with the RPF if you
 * need to use a not yet implemented rpc-method
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
require_once __DIR__ . '/../../bootstrap.php';  // Load the framework
$rpf = new \rpf\system\rpf();                   // Setup rpf

$rpf
    ->getApi()                                  // Optional: Load the api
    ->getUser()                                 // Optional: Load the user-module
    ->httpAuth();                               // Optional: Send http-auth if you need to authenticate first

$result = $rpf
    ->getApi()                                  // Load the api
    ->getPlaceholder()                          // Initialize and get the placeholder-module
    ->setMethod('bbSomeClass::someMethod')      // Set the name of the required method, eg. bbDomain::readEntry
    ->addParam('param1', true)                  // Set the parameters
    ->addParam('param2', 'string')              // ...
    ->addParam('param3', ['a', 'r', 'r', 'y'])  // ...
    ->get();                                    // Send api-call and fetch the result

print_r($result);


/*
 * Access within a rpf-extension
 */
require_once __DIR__ . '/../../bootstrap.php';          // Load the framework
use \rpf\system\module\log;                             // Optional: Link the logger-class

class myNewExtension extends \rpf\api\apiModule         // Create new rpf-extension
{
    public function myFunction()                        // Create new method
    {
        log::debug('Starting export', __METHOD__);      // Optional: Adding debug-notification to syslog

        return $this->getApi()                          // Load the api
            ->getPlaceholder()                          // Initialize and get the placeholder-module
            ->setMethod('bbSomeClass::someMethod')      // Set the name of the required method, eg. bbDomain::readEntry
            ->addParam('param1', true)                  // Set the parameters
            ->addParam('param2', 'string')              // ...
            ->addParam('param3', ['a', 'r', 'r', 'y'])  // ...
            ->get();                                    // Send api-call and fetch the result
    }
}

$myExtension = new myNewExtension();                    // Direct initialization of your new extension

$myExtension
    ->getApi()                                          // Optional: Load the api
    ->getUser()                                         // Optional: Load the user-module
    ->httpAuth();                                       // Optional: Send http-auth if you need to authenticate first

$result = $myExtension
    ->myFunction();                                     // Execute your new method
