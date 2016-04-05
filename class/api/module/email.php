<?php namespace rpf\api\module;

use rpf\api\apiModule;

require_once __DIR__.'/../apiModule.php';

/**
 * RP2-API-Module email
 *
 * @package system\module
 */
class email extends apiModule
{
    /**
     * Loads all email-accounts from a given order-id (oeid)
     *
     * @param (int|array) $oeids
     * @return $this
     * @throws \Exception
     */
    public function loadByOeid($oeids)
    {
        if (is_int($oeids))
        {
            $oeids[] = $oeids;
        }

        if (!is_array($oeids))
        {
            throw new \Exception(__CLASS__.'::'.__METHOD__.' expects an array, '.gettype($oeids).' given.');
        }

        foreach($oeids as $oeid)
        {
            if (!is_int($oeid))
            {
                throw new \Exception(__CLASS__.'::'.__METHOD__.' expects an int-array, '.gettype($oeid).' in array given.');
            }
            $this->orders[$oeid] = $this->system->call('bbEmail::readAccount', ['oeid' => $oeid, 'return_array' => 1]);
        }
        return $this;
    }

    public function loadAll($param = false, $runOnce = true)
    {
        if ($runOnce && !$this->runOnce(__METHOD__)) return $this;

        if ($oeid == false && $param == false)
        {
            $this->data = $this->system->call('bbEmail::readAccount', ['return_array' => 1]);
        }
        else
        {
            throw new \Exception(__METHOD__."($oeid, $param, $runOnce) is not implemented yet", 501);
        }

        return $this;
    }

    /**
     * Counts all set  email-addresses per order
     *
     * @param (int|array|bool) $oeid
     * @param bool $runOnce
     * @return $this
     * @throws \Exception
     */
    public function loadOrderMailCount($oeid = false, $runOnce = true)
    {
        if ($runOnce && !$this->runOnce(__METHOD__)) return $this;

        if ($oeid !== false)
        {
            // TODO: implement int+array
            throw new \Exception("Not implemented yet", 501);
        }

        $result = $this->system->call('bbEmail::readAccount', ['return_array' => 1]);

        foreach ($result as &$row)
        {
            if (!isset($this->orders[$row['oeid']]['mail_addresses']))
            {
                $this->orders[$row['oeid']]['mail_addresses'] = 1;
            }
            else
            {
                $this->orders[$row['oeid']]['mail_addresses']++;
            }
        }
        return $this;
    }

    /**
     * Return all loaded e-mail-addresses indexed by order-id
     *
     * @return array[123] = array (...)
     */
    public function getIndexedByOrderId()
    {
        if (!is_array($this->data) || empty($this->data))
        {
            return array();
        }
        else
        {
            foreach ($this->data as &$row) {
                $return[$row['oeid']] = &$row;
            }
            return $return;
        }
    }

    /**
     * Return all loaded e-mail-addresses indexed by e-mail-address
     *
     * @return array['some@mail.com'] = array(...)
     */
    public function getIndexedByEmail()
    {
        if (!is_array($this->data) || empty($this->data))
        {
            return array();
        }
        else
        {
            foreach ($this->data as &$row) {
                // TODO Implement
                $return[$row['xxxx']] = &$row;
            }
            return $return;
        }
    }




}