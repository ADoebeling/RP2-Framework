# RP² API-Framework

We're about the develop a framework for the [RP² API](https://doku.premium-admin.eu/doku.php/api).
The target is to make it more easy to use and work with the API and provide some often-used functions ready-to-use.

## Structure: /class/api/

* api.php   
Main class which is included by any RP²-based extension   
  
* apiModule.php  
Mother-Class for all API-Modules
 
## Structure: /class/api/module/
 
* customer.php   
Interface for bbRpc->bbCustomer, reachable at `$this->system->customer`

* order.php  
Interface for bbRpc->bbOrder, reachable at `$this->system->order`

* email.php  
Interface for bbRpc->bbCustomer, reachable at `$this->system->email`

# RP² Extension-Framework

We've developed a bunch of extensions over the years for the RP², but had to recognize, that they don't follow any
structures. We're about to develop a framework to attach any future extensions into this clean and high-performance 
system.

## Structure: /class/extension/

* extension.php   
Main class which is included by any RP²-Extension-Script
  
* extensionModule.php  
Mother-Class for all Extension-Modules

## Structure: /class/extension/module/
 
* invoiceTextExport.php   
Extension to provide a plaintext copy&paste-interface for invoices

* mailExport.php  
Extension to provide a plaintext copy&paste-interface for customer email-configurations

* mgntRatioExport.php  
Extension to provide a csv-export of your hosting-based management ratios

## Structure: /function/

Some helper-functions

## Structure: /script/

Some test-scripts and cronjobs

## Structure: /htdocs/

Our public accessible extensions that use http-auth to login into RP²

# RP² Extensions

WIP

# Credits

Author: [Andreas Döbeling](http://xing.doebeling.de)  
Copyright: [1601.production siegler&thümmler ohg](http://www.1601.com/hosting/)  
License: [cc-by-sa](https://creativecommons.org/licenses/by-sa/3.0)  

