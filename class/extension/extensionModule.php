<?php

namespace www1601com\df_rp\extension;

/**
 * Model for all RP2-Extension-Modules
 *
 * @package www1601com\df_rp\extension
 */
class extensionModule {

    /**
     * @var object extension
     */
    protected $system;

    /**
     * Builds the class-structure
     *
     * @param extension $system
     */
    public function __construct(extension &$system)
    {
        $this->system = &$system;
    }
}