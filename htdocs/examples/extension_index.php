<?php
/**
 * This example shows how to work with the RPF if you
 * want to use with a single extension
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link https://xing-ad.1601.com
 * @link https://www.1601.com
 */


/*
 * Direct access a extension
 */
require_once __DIR__ . '/../../bootstrap.php';

$index = new \rpf\extension\module\index();
$index
    ->showIndex();