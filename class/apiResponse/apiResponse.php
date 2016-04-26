<?php

namespace rpf\apiResponse;
use rpf\system\module;

/**
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link https://xing-ad.1601.com
 * @link https://www.1601.com
 */
class apiResponse extends module
{
    /**
     * @var array
     */
    protected $apiResponse = [];

    /**
     * @var string
     */
    protected $class = NULL;

    /**
     * @var array
     */
    protected $objects = [];

    /**
     * @var array
     */
    protected $resource = [];


    public function initialize($class, $apiResponse)
    {
        if (!isset($this->class))
        {
            $this->class = $class;
            $this->apiResponse = $apiResponse;
            $this->setAll();
        }
        return $this;
    }

    protected function set($pk)
    {
        if (isset($this->apiResponse[$pk]))
        {
            if (!isset($this->objects[$pk]))
            {
                //$this->objects[$pk] = new $this->class($this->apiResponse[$pk]);
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    protected function setAll()
    {
        foreach ($this->apiResponse as $pk => $value)
        {
            if (!isset($this->objects[$pk]))
            {
                $this->objects[$pk] = new $this->class($value);
            }
            $this->resource[$pk] =& $this->objects[$pk];
        }
        return true;
    }

    public function getResource()
    {

        if (!isset($this->class) || !isset($this->apiResponse))
        {
            throw new module\exception("It seems like you forgot to initialize the apiResponse?");
        }

        if (empty($this->resource))
        {
            $this->setAll();
            return false;
        }
        return array_shift($this->resource);
    }

    /**
     * @param $pk
     * @return object|bool
     */
    public function get($pk)
    {
        return $this->set($pk) ? $this->objects[$pk] : false;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $this->setAll();
        return $this->objects;
    }
}

