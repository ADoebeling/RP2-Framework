<?php

require_once '../../../class/system/system.php';

$e = new \www1601com\df_rp\extension\extension();
$e -> httpAuth();

$e -> domainExport -> buildDomainList()->sendDownloadCsv();