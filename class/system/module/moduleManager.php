<?php

namespace rpf\system\module;
use rpf\system\rpf;

/**
 * RPF ModuleManager
 *
 * Manages all modules (rpf-core, api-modules, extension-modules)
 * and stores them in $GLOBALS['rpfModule']
 *
 * This class can and should ONLY get instantiated by rpf\module()
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link https://xing-ad.1601.com
 */
class moduleManager
{
    /**
     * @var array ['objectName' => object]
     */
    protected $module = array();

    public function __construct()
    {
        if (!isset($GLOBALS['rpfModule']) || $this != $GLOBALS['rpfModule'])
        {
            //throw new exception('Sorry, class can\'t get used outside of rpf / $GLOBALS');
        }
        if (!isset($this->module['rpf\system\rpf']))
        {
            //$this->add(rpf::class);
        }
    }

    public function get($name, $param = NULL)
    {
        if (!is_string($name))
        {
            $type = gettype($name);
            throw new exception("Name (=string) of object expected, $type given");
        }
        if (!isset($this->module[$name]))
        {
            $this->add($name, $param);
        }
        return $this->module[$name];
    }

    /**
     * Add a module
     *
     * @param string|object $nameOrObject
     * @throws \Exception
     * @return bool true
     */
    public function add($nameOrObject, $param = NULL)
    {
        if (is_string($nameOrObject))
        {
            if (!isset($this->module[$nameOrObject]))
            {
                //log::debug("Instantiating $nameOrObject()", __METHOD__."($nameOrObject)");
                $this->module[$nameOrObject] = new $nameOrObject($param);
            }
            else
            {
                throw new exception("Can't set object by name '$nameOrObject' because it already exists");
            }
        }
        else if (is_object($nameOrObject))
        {
            $name = get_class($nameOrObject);
            if (!isset($this->module[$name]))
            {
                $this->module[$name] = new $nameOrObject;
            }
            else
            {
                throw new exception("Can't set object by instance of '$name' because it already exists");
            }
        }
        else
        {
            $type = gettype($nameOrObject);
            throw new exception("I've no idea what you're trying, but I'm sure that you can't add a $type to the global module-class");
        }
        return true;
    }
}