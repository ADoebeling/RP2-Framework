<?php

namespace rpf\extension\module;
use rpf\extension\extensionModule;

require_once '../bootstrap.php';

/**
 * Store full result set of emailReadAccount as 
 * serialized array
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link http://xing.doebeling.de
 * @link https://www.1601.com
 */
class getMailSerialized extends extensionModule
{
    public function get()
    {
        return $this
            ->getApi()
            ->getEmailReadAccount()
            ->addStat()
            ->addArTime()
            //->addRpc()
            ->addSievefilterOverview()
            //->addUsed()
            ->getArray();
    }

    public function store($filename = __DIR__.'/data/mail.serialized.php')
    {
        file_put_contents($filename, serialize($this->get()));
        echo "Serialized output stored at $filename";
    }
}

$gms = new getMailSerialized();
$gms->store();