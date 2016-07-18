<?php

namespace rpf\extension\module;
use rpf\system\module\log;

/**
 * Export all ftp accounts
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling
 * @link http://xing.doebeling.de
 * @link https://www.1601.com
 */
class csvExportFtp extends csvExport
{
    public function build($sort = true)
    {
        $ftps = $this
            ->getApi()
            ->getFtpReadEntry()
            ->getArray();

        if (!is_array($ftps))
        {
            log::warning('There are no ftp accounts you could export?!', __METHOD__);
        }
        else
        {
            foreach ($ftps as $ftp)
            {
                $this->csv[] =
                    [
                        'Order Nr.' => empty($ftp['ordnr']) ? '' : $ftp['ordnr'],
                        'Path' => $ftp['relpfad'],
                        'Username' => $ftp['username'],
                        'Password' => $ftp['password'],
                    ];
            }
        }
        return parent::build($sort);
    }

    public function execute($filename = 'QuotaAndSsh')
    {
        return parent::execute($filename);
    }
}