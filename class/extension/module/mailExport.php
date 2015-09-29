<?php namespace www1601com\df_rp\extension;

require_once __DIR__.'/../extensionModule.php';
require_once __DIR__.'/../../../function/array_merge_recursive_distinct.php';
class mailExport extends extensionModule {


    /**
     * Return all active orders with existing e-mail-adresses
     *
     * @return array
     */
    public function getOrderList()
    {
        $result = array_merge_recursive_distinct(
            $this->system->order->loadAll()->getOrder(),
            $this->system->email->loadOrderMailCount()->getOrder()
        );

        foreach ($result as $row)
        {
            if ($row['has_active_tariff'] == 1 && isset($row['mail_addresses']) && $row['mail_addresses'] > 0)
            $return[$row['ordnr']] =
                [
                    'oeid' => $row['oeid'],
                    'cus_company' => $row['cus_company'],
                    'cus_first_name' => $row['cus_first_name'],
                    'cus_last_name' => $row['cus_last_name'],
                    'mail_addresses' => $row['mail_addresses']
                ];
        }
        return $return;
    }

    /**
     * Returns a description of the current e-mail-config for a/some given orders (oeids)
     *
     * @param (int|array) $oeids
     * @return $this
     */
    public function getConfigDesc($oeids)
    {
        $oeids = is_int($oeids) ? [$oeids] : $oeids;

        print_r($this->system->email->load($oeids)->getData());
        echo "jo";
        return $this;
    }

}