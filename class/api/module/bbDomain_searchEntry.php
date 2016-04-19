<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbDomain::searchEntry
 *
 * @package system\module
 */
class bbDomain_searchEntry extends apiModule
{
    protected $rpcMethod = 'bbDomain::searchEntrys';

    /**
     * Set filter on name
     *
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->addParam('name', (string) $name);
    }

    /**
     * Set filter on pk
     *
     * @param $pk
     * @return $this
     */
    public function setPk($pk)
    {
        return $this->addParam('pk', $pk);
    }

    /**
     * return Tree
     *
     * @param $bool
     * @return $this
     */
    public function addReturnTree($bool)
    {
        return $this->addParam('return_tree', $bool);
    }
}