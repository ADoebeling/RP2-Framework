<?php

namespace rpf\extension\module;
use rpf\system\module\log;

/**
 * Class mysqlExport
 *
 * This class provides methods to export all mysql-dbs into a CSV-Export
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link http://xing.doebeling.de
 * @link https://www.1601.com
 */
class csvExportMysql extends csvExport
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
        $orders = $this->getApi()->getOrderReadEntry()->get(true, 'ordnr');
        $mysql = $this->getApi()->getMysqlReadEntry()->get();

        if (!is_array($mysql))
        {
            log::warning('There are no mysql-dbs you could export?!', __METHOD__);
        }
        else
        {
            foreach ($mysql as $row)
            {
                // Get Mysql-Version
                switch ($row['hostip'])
                {
                    case '127.0.0.1':
                        $version = 'MySQL 3 (DEPRECATED)';
                        break;

                    case '127.0.0.2':
                        $version = 'MySQL 4 (DEPRECATED)';
                        break;

                    case '127.0.0.3':
                        $version = 'MySQL 5';
                        break;
                }

                $customer =  isset($orders[$row['ordnr']]) ? $this->getCustomerNameFormatted($orders[$row['ordnr']]) : '';
                $this->csv[] = [
                    'Customer'          => $customer,

                    'Order'             => $row['ordnr'],
                    'Database'          => $row['name'],
                    'Description'       => $row['notice'],

                    'IP (local)'        => $row['hostip'],
                    'IP (external)'     => $row['extip'],
                    'Version'           => $version,

                    'Password'          => $row['password']
                ];
            }
        }
        ksort($this->csv);
        return $this;
    }
}