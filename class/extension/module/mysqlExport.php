<?php

namespace rpf\extension\module;
use rpf\extension\extensionModule;
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
 *
 * @TODO: Add RP2-Order to export
 */
class mysqlExport extends extensionModule
{
    protected $csvFields = array();

    /**
     * Building a list of all domains, matching set filter
     *
     * - OrderNr
     * - (Sub-)Domain
     * - PHP-Version
     * - Target
     */
    public function buildList()
    {
        $orders = $this->getApi()->getOrderReadEntry()->get(true, 'ordnr');

        $result = $this->getApi()->getMysqlReadEntry()->get();

        if (!is_array($result))
        {
            log::warning('There are no mysql-dbs you could export?!', __METHOD__);
        }
        else
        {
            foreach ($result as $row)
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
                $pk = isset($orders[$row['ordnr']]) ? $customer : '000_'.$row['pk'];

                $this->csvFields[$pk] = [
                    'Customer'          => $customer,
                    'Order'             => $row['ordnr'],
                    'IP (local)'        => $row['hostip'],
                    'IP (external)'     => $row['extip'],
                    'Version'           => $version,
                    'Name'              => $row['name'],
                    'Description'       => $row['notice'],
                    'Password'          => $row['password']
                ];
            }
        }
        ksort($this->csvFields);
        return $this;
    }

    protected function getCsv()
    {
        if (empty($this->csvFields)) $this->buildList();

        $csv = "";
        foreach($this->csvFields as $row)
        {
            // Headline
            if (empty($csv))
            {
                foreach ($row as $title => $value)
                {
                    $csv .= "$title;";
                }
                $csv .="\n";
            }

            // Content
            foreach ($row as $value)
            {
                $csv .= "$value;";
            }
            $csv .= "\n";
        }
        return $csv;
    }

    /**
     * Send application/csv-header and starts downloading the csv
     *
     * @param string $filename
     * @return $this
     * @throws \Exception
     */
    public function sendDownloadCsv($filename = NULL)
    {
        $filename = $filename !== NULL ? $filename : 'MysqlExport_'.date('ymd').'_1SRV.csv';
        log::info("Download started: $filename", __METHOD__);
        header('Content-Type: text/html; charset=iso-8859-15');
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=\"$filename\";");
        echo utf8_decode($this->getCsv());
        return $this;
    }
}