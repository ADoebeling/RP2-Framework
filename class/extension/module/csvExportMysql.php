<?php

namespace rpf\extension\module;
use rpf\system\module\log;

/**
 * Export all MySQL databases with details as csv:
 * - Customer name
 * - Order id
 * - Database name
 * - Database description
 * - IP (local)
 * - IP (external)
 * - Version
 * - Password
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link http://xing.doebeling.de
 * @link https://www.1601.com
 */
class csvExportMysql extends csvExport
{
    public function build($sort = true)
    {
        $orders = $this
            ->getApi()
            ->getOrderReadEntry()
            ->getArray();

        $mysql = $this
            ->getApi()
            ->getMysqlReadEntry()
            ->getArray();

        if (!is_array($mysql))
        {
            log::warning('There are no mysql-dbs you could export?!', __METHOD__);
        }
        else
        {
            foreach ($mysql as $row)
            {
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

                    default:
                        $version = 'UNKNOWN';
                }

                $this->csv[] = [
                    'Customer'          => isset($orders[$row['ordnr']]) ? $this->getCustomerNameFormatted($orders[$row['ordnr']]) : '',
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
        return parent::build($sort);
    }

    public function execute($filename = 'Mysql')
    {
        return parent::execute($filename);
    }
}