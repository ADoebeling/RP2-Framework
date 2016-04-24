<?php

namespace rpf\extension\module;
use rpf\api\module\bbDomain_readEntry;
use rpf\extension\extensionModule;
use rpf\system\module\exception;
use rpf\system\module\log;

/**
 * Class domainExport
 *
 * This class provides methods to export all domains into a CSV-Export
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling
 * @link http://xing.doebeling.de
 * @link https://www.1601.com
 * @version 0.1.160401_1ad
 *
 * @package system\extension
 */
class csvExportDomain extends csvExport
{
    /**
     * Building a list of all domains, matching set filter
     *
     * - OrderNr
     * - (Sub-)Domain
     * - PHP-Version
     * - Target
     */
    public function buildCsv()
    {
        $domains = $this->getApi()->getDomainReadEntry()->addSettings()->addSubdomain()->get(true, 'name');
        $orders = $this->getApi()->getOrderReadEntry()->get(true, 'ordnr');

        if (!is_array($domains))
        {
            log::warning('There are no domains you could export?!', __METHOD__);
        }
        else
        {
            foreach ($domains as $domain)
            {
                $order = isset($domain['ordnr']) && !empty($domain['ordnr']) ? $orders[$domain['ordnr']] : NULL;

                $customer = $this->getCustomerNameFormatted($order);
                $phpVersion = &$domain['settings']['php_version'];
                if (isset($domain['subdomain']) && is_array($domain['subdomain']))
                foreach ($domain['subdomain'] as $subdomain)
                {
                    $subdomainName = $domain['name'] != $subdomain['name'] ? $subdomain['name'] : '';
                    $this->csv[] =
                        [
                            'Customer' => $customer,
                            'Order' => $subdomain['ordnr'],
                            'Domain' => $domain['name'],
                            'Subdomain' => $subdomainName,
                            'PHP Version' => $phpVersion,
                            'AuthCode' => isset($domain['authcode']) ? $domain['authcode'] : '',
                            'Target' => $subdomain['target']
                        ];
                }
            }
        }
        ksort($this->csv);
        return $this;
    }

    public function execute($filename = 'Domain')
    {
        parent::execute($filename);
    }
}