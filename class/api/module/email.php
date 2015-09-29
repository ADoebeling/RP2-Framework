<?php namespace www1601com\df_rp\module;

require_once __DIR__.'/../apiModule.php';

/**
 * RP2-API-Module email
 *
 * @package www1601com\df_rp\module
 */
class email extends apiModule
{
    /**
     * Loads all email-accounts into $this->data
     *
     * @return $this
     */
    public function loadAll()
    {
        $this->data = $this->system->call('bbEmail::readAccount', ['return_array' => 1]);
        return $this;
    }

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
            $this->data[$oeid] = $this->system->call('bbEmail::readAccount', ['oeid' => $oeid, 'return_array' => 1]);
        }
        return $this;
    }

    /**
     * Counts all set  email-addresses per order
     *
     * @param (int|array|bool) $oeid
     * @return $this
     * @throws \Exception
     */
    public function loadOrderMailCount($oeid = false)
    {
        if ($oeid !== false)
        {
            // TODO: implement int+array
            throw new \Exception("Not implemented yet", 501);
        }

        $result = $this->system->call('bbEmail::readAccount', ['return_array' => 1]);

        foreach ($result as &$row)
        {
            if (!isset($this->order[$row['oeid']]['mail_addresses']))
            {
                $this->order[$row['oeid']]['mail_addresses'] = 1;
            }
            else
            {
                $this->order[$row['oeid']]['mail_addresses']++;
            }
        }
        return $this;
    }


}