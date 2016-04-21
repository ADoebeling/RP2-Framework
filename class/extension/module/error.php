<?php

namespace rpf\extension\module;
use rpf\extension\extensionModule;

/**
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link https://xing-ad.1601.com
 */
class error extends extensionModule
{
    public static function show($title, $text, $code = '')
    {
        include __DIR__.'error/error.tpl.php';
    }
}