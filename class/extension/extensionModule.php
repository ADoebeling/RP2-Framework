<?php

namespace rpf\extension;
use rpf\system\module;

/**
 * Model for all RP2-Extension-Modules
 *
 * @package system\extension
 */
class extensionModule extends module
{
    /**
     * @param mixed $executeParam
     */
    public function __construct($executeParam = false)
    {
        parent::__construct();
        if ($executeParam !== false)
        {
            $this->execute($executeParam);
        }
    }

    /**
     * Default-Method, should be overwritten by child
     *
     * @param $param
     * @throws module\exception
     */
    public function execute($param)
    {
    }
}