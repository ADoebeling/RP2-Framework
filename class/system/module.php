<?php

namespace rpf\system;
use rpf\api\api;
use rpf\extension\extension;
use rpf\system\module\exception;
use rpf\system\module\log;
use rpf\system\module\moduleManager;

/**
 * Generic RPF Module
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link https://xing-ad.1601.com
 * @package system
 */
class module
{
    /**
     * @var moduleManager
     */
    private $module;

    public function __construct()
    {
        if (!isset($GLOBALS['rpfModule']))
        {
            $GLOBALS['rpfModule'] = new moduleManager();
        }
        $this->module =& $GLOBALS['rpfModule'];
    }

    /**
     * Get the global api-object
     * @return api
     */
    public function getApi()
    {
        return $this->getModule(api::class);
    }

    /**
     * Get the global extension-object
     * @return extension
     */
    public function getExtension()
    {
        return $this->getModule(extension::class);
    }


    /**
     * Returns the system-instance of the requested object.
     * If the instance doesn't exists, it builds the instance into
     * $this->module[$name]
     *
     * @param $name
     * @return object
     */
    protected function getModule($name)
    {
        return $this->module->get($name);
    }

    
    protected function addModule($nameOrObject)
    {
        // Not in use?
        throw new exception('not in use?');
        return $this->module->add($nameOrObject);
    }
}