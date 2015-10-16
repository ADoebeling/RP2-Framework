<?php

namespace www1601com\df_rp\extension;

/**
 * Model for all RP2-Extension-Modules
 *
 * @package www1601com\df_rp\extension
 */
class extensionModule {


    /**
     * @var extension $system
     */
    protected $system = extension;

    /**
     * @var array module-data
     */
    protected $data = array();

    /**
     * Builds the class-structure
     *
     * @param extension $system
     */
    public function __construct(extension &$system)
    {
        $this->system = &$system;
    }

    /**
     * Returns a array with the previous load data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}