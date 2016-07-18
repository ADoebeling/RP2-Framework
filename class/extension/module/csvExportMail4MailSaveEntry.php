<?php

namespace rpf\extension\module;
use rpf\system\module\log;

/**
 * One-Time-Export of all mail accounts as CSV for
 * mailSaveEntry
 *
 * This class will stop working from 2016-07-20
 * https://www.df.eu/forum/threads/79388
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link http://xing.doebeling.de
 * @link https://www.1601.com
 */
class csvExportMail4MailSaveEntry extends csvExport
{
    public function build($sort = true)
    {
        $mails = $this
            ->getApi()
            ->getEmailReadAccount()
            ->getArray();

        if (!is_array($mails))
        {
            log::warning('There are no mail accounts you could export?!', __METHOD__);
        }
        else
        {
            foreach ($mails as $row)
            {
                if (isset($row['storage']['size']) && $row['storage']['size'] > 0 || isset($row['exchange_storage']))
                {
                    $this->csv[] = [
                        'seid'              => $row['seid'],
                        'mail'              => $row['address'][0]['email'],
                        'password'          => $row['password'],
                        'date'              => $row['upd_date'],
                        'comment'           => 'Initial import by csvExportMail4MailSaveEntry: '.date("r")
                    ];
                }
            }
        }
        return parent::build($sort);
    }

    public function execute($filename = 'InitialMailImport4MailSaveEntry')
    {
        return parent::execute($filename);
    }
}