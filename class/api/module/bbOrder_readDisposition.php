<?php

namespace rpf\api\module;
use rpf\api\apiModule;

/**
 * Implementation of bbOrder:readDisposition
 * @see https://doku.premium-admin.eu/doku.php/api/methoden/bborder/readdisposition
 * @package system\module
 */
class bbOrder_readDisposition extends apiModule
{
    const rpcMethod = 'bbOrder::readDisposition';

    /**
     * @param int $oeid
     * @return $this|bbOrder_readDisposition
     */
    public function setFilterOrderId($oeid = 0)
    {
        return $this->addParam('oeid', (int) $oeid);
    }

    /**
     * @param bool|false $accountable
     * @return $this|bbOrder_readDisposition
     */
    public function setFilterAccountable($accountable = false)
    {
        return $this->addParam('return_accountable', (bool) $accountable);
    }

    /**
     * @param bool|false $active
     * @return $this|bbOrder_readDisposition
     */
    public function setFilterActive($active = false)
    {
        return $this->addParam('return_active', (bool) $active);
    }


    protected function getPatchedResult($result = array())
    {
        /*
         * Patch for stupid/none implemented exchange // df-ticket #5402091 / #5401975
         */
        if (strpos($result['so_type'], 'hosting_zusatz_exchange_') === 0)
        {
            $regex = "/^(\\S*@\\S*\\.\\S{2,8})/";
            preg_match($regex, $result['name'], $matches);
            $result['_patch_exchange_account'] = $matches[1];
        }

        /*
         * Patch against stupid/none implemented ssl-certificate // df-ticket #5401975
         */
        else if($result['so_type'] == 'ssl')
        {
            $regEx = "/(\\S*\\.\\S*\\.\\S*)/";
            preg_match($regEx, $row['descr'], $domain);
            $result['_patch_ssldomain'] = $domain[1];
        }
    }




    public function getDisposition($ordNr = false)
    {
        if ($ordNr !== false && isset($this->disposition[$ordNr]))
        {
            return $this->disposition[$ordNr];
        }
        else if (!empty($this->disposition))
        {
            return $this->disposition;
        }
        else
        {
            throw new \Exception('We don\'t have any dispositions', 404);
        }
    }
}