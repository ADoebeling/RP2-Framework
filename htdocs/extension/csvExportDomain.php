<?php

require_once __DIR__ . '/../../bootstrap.php';

$rpf = new \rpf\system\rpf();

$rpf
    ->getApi()
    ->getUser()
    ->httpAuth();

$rpf
    ->getExtension()
    ->getDomainExport()
    ->sendCsvDownload('Domains');



