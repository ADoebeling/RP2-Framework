<?php

namespace www1601com\df_rp\extension;
use www1601com\df_rp\api\module\bbDomain_readEntry;
use www1601com\df_rp\system\module\log;

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
 * @package www1601com\df_rp\extension
 */
class domainExport extends extensionModule
{
    protected $domainList = array();

    /**
     * Building a list of all domains, matching set filter
     *
     * - OrderNr
     * - (Sub-)Domain
     * - PHP-Version
     * - Target
     */
    public function buildDomainList()
    {
        /** @var bbDomain_readEntry $domains */
        $domains = $this->system->bbDomain_readEntry;

        $result = $domains
            ->addSettings()
            ->addSubdomain()
            ->get();
        foreach ($result as $domain)
        {
            $phpVersion = &$domain['settings']['php_version'];
            if (isset($domain['subdomain']) && is_array($domain['subdomain']))
            foreach ($domain['subdomain'] as $subdomain)
            {
                $this->domainList[$subdomain['name']] =
                    [
                        'name' => $subdomain['name'],
                        'orderNr' => $subdomain['ordnr'],
                        'phpVersion' => $phpVersion,
                        'target' => $subdomain['target']
                    ];
            }
        }
        return $this;
    }

    protected function getCsv()
    {
        if (empty($this->domainList)) $this->buildDomainList();
        $csv = "(Sub-)Domain;RP2-Order;PHP-Version;Target\n";
        foreach($this->domainList as $domain => $fields)
        {
            foreach ($fields as $row) $csv .= "$row;";
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
        $filename = $filename !== NULL ? $filename : 'DomainExport_'.date('ymd').'_1SRV.csv';
        log::info("Download started: $filename");
        header('Content-Type: text/html; charset=iso-8859-15');
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=\"$filename\";");
        echo utf8_decode($this->getCsv());
        return $this;
    }

}