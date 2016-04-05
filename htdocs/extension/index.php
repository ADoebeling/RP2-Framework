<?php

require_once __DIR__ . '/../../bootstrap.php';

$rpf = new \rpf\system\rpf();

$rpf
    ->getExtension()
    ->getIndex()
    ->showIndex();