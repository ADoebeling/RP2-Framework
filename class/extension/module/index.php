<?php

namespace rpf\extension\module;
use rpf\extension\extensionModule;
use rpf\system\module\log;

/**
 * Class Index
 *
 * Displays a startpage
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link https://xing-ad.1601.com
 * @package system
 */
class index extends extensionModule
{
    public function showIndex()
    {
        log::debug('Displaying Index-Page',__METHOD__);
        echo file_get_contents(__DIR__.'/index/index.tpl');
    }
}