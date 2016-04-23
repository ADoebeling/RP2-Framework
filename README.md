# RPF - The DomainFactory RP²-Framework

The RPF provides a nicely ide-compatible php-interface to the DomainFactory [RP²-API](https://doku.premium-admin.eu/doku.php/api/methoden/start)  for developers and collects a bunch of ready-to-use extensions for admins and customers. The system is modular structured and can be extended easily.

## RPF-Extensions

* **DomainExport**  
CSV-Export of all (sub-)domains with Order-Name, PHP-Version and target.  
Can be used to check for deprecated domain-settings in the seventh server-generation  
_Status: Implemented and published_

* **contaoLogChecker** (WIP)  
Exports all contao-logs from all hosted databases  
_Status: Implemented but not published yet_

* **emailExport** (WIP)  
Exports the complete e-mail-configuration as copy&paste template for customer service  
_Status: Implemented but not published yet_

* **inconsistencyChecker** (WIP)  
Checks and alerts for some rp2-inconsistency like active unregistered domains  
_Status: Implemented but not published yet_

* **invoiceTextExport** (WIP)  
Exports the invoice-texts for each order as copy&paste template  
_Status: Implemented but not published yet_

* **mysqlBackup** (WIP)  
Backup & Restore-Manager for mysql-databases  
_Status: Not implemented yet_

* **mysqlExport** (WIP)  
CSV-Export of all mysql-databases.  
_Status: Implemented and published_
 
* **mgntRatioExport** (WIP)  
Calculation-sheet with costs and contribution margin for every article  
_Status: Implemented but not published yet_

* **siteMonitoring** (WIP)   
Monitor all (Sub-)Domains based on a screenshot-diff  
_Status: Implemented but not published yet_


### Installation of RPF-Extensions

* Download the [latest release](https://github.com/ADoebeling/RP2-Framework/releases)  and unzip it on your server  
(On Bash: `wget https://github.com/ADoebeling/RP2-Framework/archive/XXX.tgz && tar xzf XXX.tgz`)  
* Setup a new subdomain (with ssl-wildcard-cert and php 5.6) and point the target to htdocs
* Open the subdomain in your browser. You're done.
* By default no configuration is necessary 

## RPF-API

For developers it is quite easy to use the API. Do you need to export all domains from OrderId XY with nameserver settings?

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
    ->getApi()                                  // Load the api
    ->getUser()                                 // Load the user-module
    ->httpAuth();                               // Send http-auth if you need to authenticate first
```


### Using the RPF-API

* Install the rpf-extensions like described above
* Have a look to the [example-folder](https://github.com/ADoebeling/RP2-Framework/tree/master/htdocs/examples) and the [api-documentation](http://adoebeling.github.io/RP2-Framework/)
* Have a look to the 
* Create your first extension and start over
* The code is 100% ide-compatible and well documented, so code-completion works fine. 
* Every action and every call are getting detailed logged an can be monitored with `tail -f logs/syslog_YYMMDD_1SRV.log` on bash.


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


# Contact

### Support? FR/Bug? Extension-Request?

I'ld be happy to hear from you! Please send me your Feature-Request or Bug-Report as GitHub-Issue. If you don't have a GitHub-Account please post into the DF-Forum.
If you need paid support contact me on support@1601.com or give me a call: `+49 9131 506770` and ask for Andreas Döbeling.

### Credits

Author: [Andreas Döbeling](http://xing.doebeling.de)  
Copyright: [1601.production siegler&thümmler ohg](http://www.1601.com/hosting/)  
License: [cc-by-sa](https://creativecommons.org/licenses/by-sa/3.0)