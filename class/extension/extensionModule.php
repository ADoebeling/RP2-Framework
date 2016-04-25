<?php

namespace rpf\extension;
use rpf\system\module;

/**
 * Model for all RP2-Extension-Modules
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
     * Default-Method for data building, should be overwritten by child
     *
     * The default build-function
     * @return $this
     */
    protected function build($param = NULL)
    {
        return $this;
    }

    /**
     * Default-Method for executing, should be overwritten by child
     *
     * @param mixed $param
     * @return $this
     */
    public function execute($param = NULL)
    {
        return $this->build($param);
    }
}