<?php

namespace www1601com\df_rp\api;

/**
 * Model for all API-Modules
 *
 * @package www1601com\df_rp\module
 */
class apiModule {

    protected $rpcClass = NULL;

    /**
     * Build the class-structure
     *
     * @param api $system
     */
    public function __construct(api &$system)
    {
        $this->system = &$system;
    }

    /*******
     * v2
     ******/

    /**
     * @var array call-params (filter, return, format, ...)
     */
    protected $params = array();

    /**
     * @var array [jscon_encode($param)] = array() unpatched api-response
     */
    protected $runtimeCache = array();

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    protected function addParam($name, $value)
    {
        $this->params[(string) $name] = $value;
        return $this;
    }

    /**
     * @param array $result
     * @return $this
     */
    protected function setRuntimeCache($result)
    {
        $this->cache[$this->getPk($this->params)] = (array) $result;
        return $this;
    }

    /**
     * @return array|bool
     */
    protected function getRuntimeCache()
    {
        if (isset($this->cache[$this->getPk($this->params)]))
        {
            return (array) $this->cache[$this->getPk($this->params)];
        }
        else
        {
            return false;
        }
    }

    /**
     * @param array $result
     * @return array
     */
    protected function getPatchedResult($result = array())
    {
        return (array) $result;
    }

    /**
     * @return $this
     */
    protected function setPatchedParams()
    {
        $this->params['return_array'] = true;

        // Fix misspelled adress/address
        foreach ($this->params as $key => $val)
        {
            if (strpos($key, 'address') !== false)
            {
                $misspelling = $key;
                str_replace('address', 'adress', $misspelling);
                $this->params[$misspelling] = $val;
            }
        }
        return $this;
    }

    /**
     * @param string $cache
     * @param bool $patchResult
     * @return array|bool
     */
    public function get($cache = 'runtime', $patchResult = true)
    {
        if ($cache == 'runtime' && $this->getRuntimeCache() !== false)
        {
            $result = $this->getRuntimeCache();
        }
        else
        {
            $result = $this->system->call($this->rpcClass, $this->params);
            $this->cache[$this->getPk($this->params)] = $result;
        }
        return $patchResult ? $this->getPatchedResult($result) : $result;
    }




    /*******
     * v1
     ******/





    /**
     * @var object api
     */
    protected $system;

    /**
     * DEPRECATED
     * @var array $order['oeid'][$key] = [$value]
     */
    protected $orders;

    /**
     * DEPRECATED
     * @var array various imported data
     */
    protected $data;

    /**
     * @var array cache for all requests
     */
    protected $cache = array();

    /**
     * @var array Stores all already executed methods
     */
    protected $runOnce = [];



    /**
     * Returns a array with the previous load data, sorted by oeid
     *
     * @return array $order['oeid'][$key] = [$value]
     */
    public function getOrders()
    {
        return $this->orders;
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

    public function runOnce($method)
    {
        if (!isset($this->runOnce[$method]))
        {
            $this->runOnce[$method] = 1;
            return true;
        }
        else
        {
            $this->runOnce[$method]++;
            return false;
        }
    }

    public function getPk(array $parameters)
    {
        return json_encode($parameters);
    }
}