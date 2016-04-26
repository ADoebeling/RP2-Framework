<?php

namespace rpf\extension\module;
use rpf\system\module\log;

/**
 * Export all domains with details as csv:
 * - Customer name
 * - Order id
 * - Domain
 * - Subdomain
 * - PHP version
 * - AuthCode
 * - Target
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling
 * @link http://xing.doebeling.de
 * @link https://www.1601.com
 */
class csvExportDomain extends csvExport
{
    public function build($sort = true)
    {
        $domains = $this
            ->getApi()
            ->getDomainReadEntry()
            ->addSettings()
            ->addSubdomain()
            ->getArray();

        $orders = $this
            ->getApi()
            ->getOrderReadEntry()
            ->getArray();

        if (!is_array($domains))
        {
            log::warning('There are no domains you could export?!', __METHOD__);
        }
        else
        {
            foreach ($domains as $domain)
            {
                // Fixing RPC-Bug: Empty Domain
                // https://github.com/ADoebeling/RP2-Framework/issues/47
                if (isset($domain['subdomain']) && is_array($domain['subdomain']))
                {
                    foreach ($domain['subdomain'] as $subdomain)
                    {
                        $this->csv[] =
                            [
                                'Customer' => isset($orders[$domain['ordnr']]) ? $this->getCustomerNameFormatted($orders[$domain['ordnr']]) : '',
                                'Order' => isset($domain['ordnr']) ? $domain['ordnr'] : '',
                                'Domain' => $domain['name'],
                                'Subdomain' => $domain['name'] != $subdomain['name'] ? $subdomain['name'] : '',
                                'PHP Version' => $domain['settings']['php_version'],
                                'AuthCode' => isset($domain['authcode']) ? $domain['authcode'] : '',
                                'Target' => $subdomain['target']
                            ];
                    }
                }
            }
        }
        return parent::build($sort);
    }

    public function execute($filename = 'Domain')
    {
        return parent::execute($filename);
    }
}