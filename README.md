# RPF - Overview about the DomainFactory RP²-Framework

The RPF provides a nicely ide-compatible php-interface to the DomainFactory [RP²-API](https://doku.premium-admin.eu/doku.php/api/methoden/start)  for developers and collects a bunch of ready-to-use extensions for admins and customers. The system is modular structured and can be extended easily.

## RPF-Extensions

* DomainExport
CSV-Export of all (sub-)domains with Order-Name, PHP-Version and target.
Can be used to check for deprecated domain-settings in the seventh server-generation
Status: Implemented and published

* contaoLogChecker (WIP)
Exports all contao-logs from all hosted databases
Status: Implemented but not published yet

* emailExport (WIP)
Exports the complete e-mail-configuration as copy&paste template for customer service
Status: Implemented but not published yet

* inconsistencyChecker (WIP)
Checks and alerts for some rp2-inconsistency like active unregistered domains
Status: Implemented but not published yet

* invoiceTextExport (WIP)
Exports the invoice-texts for each order as copy&paste template
Status: Implemented but not published yet

* mysqlExport (WIP)
CSV-Export of all mysql-databases.
Status: Not implemented yet
 
* mysqlBackup (WIP)
Backup & Restore-Manager for mysql-databases
Status: Not implemented yet

* mgntRatioExport (WIP)
Calculation-sheet with costs and contribution margin for every article
Status: Implemented but not published yet

* siteMonitoring (WIP)
Monitor all (Sub-)Domains based on a screenshot-diff
Status: Implemented but not published yet


### Installation of RPF-Extensions

* Download the [latest release](https://github.com/ADoebeling/RP2-Framework/releases)  and unzip it on your server
(On Bash: `wget https://github.com/ADoebeling/RP2-Framework/archive/XXX.tgz && tar xzf XXX.tgz`
* Setup a new subdomain (with ssl-wildcard-cert and php 5.6) and point the target to htdocs
* Open the subdomain in your browser. You're done.
* By default no configuration is necessary 

## RPF-API

For developers it is quite easy to use the API. You need to export all domains from OrderId XY with nameserver-settings?

That's easy!

```php
require_once '../bootstrap.php';                // Include the framework
$rpf = new \rpf\system\rpf();                   // Instantiate the framework

$rpf
    ->getApi()                                  // Load the API
    ->getDomainReadEntry()                      // Load the API-module domainReadEntry (bbDomain::readEntry)
    ->setOeid('ZY')                             // Optional: Get domains by hidden rp2-order-id
    ->addNameserver()                           // Optional: Add dns-records
    ->get();                                    // Return result as array, primary-key set to domain
```

You don't want to config stuff like api-user, api-password, ...?
Me too! The api-user and api-password can requested by http-auth:

```php
$rpf
    ->getApi()                                  // Optional: Load the api
    ->getUser()                                 // Optional: Load the user-module
    ->httpAuth();                               // Optional: Send http-auth if you need to authenticate first
```

The code is 100% ide-compatible, so code-completion works fine. Never again look into the api-manual.

Every action and every call are getting logged an can be monitored with `tail -f $logfile` on bash:

```
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\classLoader('rpf\system\module\log');                                     // Include /class/system/module/log.php
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\classLoader('rpf\system\module');                                         // Include /class/system/module.php
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\classLoader('rpf\system\rpf');                                            // Include /class/system/rpf.php
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\system\rpf::__construct();                                                // Starting Session
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\classLoader('rpf\system\module\moduleManager');                           // Include /class/system/module/moduleManager.php
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\system\module\moduleManager::add(\rpf\api\api);                           // Instantiating \rpf\api\api()
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\classLoader('rpf\api\apiModule');                                         // Include /class/api/apiModule.php
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\classLoader('rpf\api\api');                                               // Include /class/api/api.php
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\system\module\moduleManager::add(rpf\api\module\user);                    // Instantiating rpf\api\module\user()
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\classLoader('rpf\api\module\user');                                       // Include /class/api/module/user.php
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] bbRpc::setUrl(http://xxx.premium-admin.eu/);                                  // Setting RPC-URL
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\classLoader('rpf\api\bbRpc');                                             // Include /class/api/bbRpc.php
[Tue, 05 Apr 2016 23:57:52 +0200] [info]  bbRpc::auth(TESTUSER, *****);                                                 // Login successful from 188.194.5.164 within 0.209 sec.
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] bbRpc::setUTF8Native(true);                                                   // Set UTF-8
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\system\module\moduleManager::add(\rpf\extension\extension);               // Instantiating \rpf\extension\extension()
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\classLoader('rpf\extension\extension');                                   // Include /class/extension/extension.php
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\system\module\moduleManager::add(rpf\extension\module\domainExport);      // Instantiating rpf\extension\module\domainExport()
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\classLoader('rpf\extension\extensionModule');                             // Include /class/extension/extensionModule.php
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\classLoader('rpf\extension\module\domainExport');                         // Include /class/extension/module/domainExport.php
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\system\module\moduleManager::add(rpf\api\module\bbDomain_readEntry);      // Instantiating rpf\api\module\bbDomain_readEntry()
[Tue, 05 Apr 2016 23:57:52 +0200] [debug] rpf\classLoader('rpf\api\module\bbDomain_readEntry');                         // Include /class/api/module/bbDomain_readEntry.php
[Tue, 05 Apr 2016 23:57:54 +0200] [debug] rpf\api\apiModule::getRpcResponse();                                          // Performing RPC-Request within 1.47 sec.
[Tue, 05 Apr 2016 23:57:54 +0200] [debug] bbDomain::readEntry('return_settings' => '1','return_subdomain' => '1');      // Getting 735 rows
[Tue, 05 Apr 2016 23:57:54 +0200] [debug] rpf\system\rpf::__destruct();                                                 // Ending Session

```

### Using the RPF-API

* Install the rpf-extensions like described above
* Have a look to the example-folder
* Create your first extension and start over


### Class-Structure

* If you create a instance of rpf, the moduleManager will always stores itself to `$GLOBALS['rpfModules']`
* Every core, api and extension-module (=class) is represented by a private instance in `moduleManger()::modules[]`
* You can access all api-Methodes with `$rpf->getApi()`, e. g. `$rpf->getApi()->getDomainEntry` which represents all api-methodes of `bbDomain::readEntry`
* You can access all extensions with `$rpf->getExtension()`, e. g. `$rpf->getExtension()->getDomainExport`


### Namespaces = File-structure

The namespaces represent the file-structure

* `\class\system\rpf.php`: The core-module
* `\class\api\api.php`: The api-module
* `\class\api\module\bbDomain_readEntry.php`: The Implementation of bbDomain::readEntry() as module of the api-module
* `\class\extension\extension.php`: The extension-module
* `\class\extension\module\domainExport.php`: The extension domainExport as module of the extension-module


### Examples

Lots of examples [can be found here](https://github.com/ADoebeling/RP2-Framework/tree/master/htdocs/examples)

# Support? Feature-Request? Extension-Request? Bug?

I'ld be happy to hear from you! Please send me your FR or Bug-Report as GitHub-Issue. If you don't have a GitHub-Account please post into the DF-Forum.
If you need paid support contact me on support@1601.com or give me a call: +49 9131 506770 and ask for Andreas Döbeling.

# Credits

Author: [Andreas Döbeling](http://xing.doebeling.de)  
Copyright: [1601.production siegler&thümmler ohg](http://www.1601.com/hosting/)  
License: [cc-by-sa](https://creativecommons.org/licenses/by-sa/3.0)  

