<?php

namespace rpf\system;
use rpf\api\api;
use rpf\apiResponse\apiResponse;
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
        log::debug("Instantiating ".get_called_class(), get_called_class());
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
     * Get the global api-object
     * @return apiResponse
     */
    public function getApiResponse()
    {
        return $this->getModule(apiResponse::class);
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
     * @param mixed $param
     * @return object
     * @throws exception
     */
    protected function getModule($name, $param = NULL)
    {
        return $this->module->get($name, $param);
    }

}