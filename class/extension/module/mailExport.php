<?php namespace rpf\extension;

require_once __DIR__ . '/../../extensionModule.php';
//require_once '../../../../function/array_merge_recursive_distinct.php';
class mailExport extends extensionModule {


    /**
     * Return all active orders with existing e-mail-adresses
     *
     * @dependencies
     * @return array
     */
    public function getOrderList()
    {
        // Load dependencies
        $this->system->orders->loadAll();
        $this->system->emails->loadOrderMailCount();

        $data = array_merge_recursive_distinct(
            $this->system->order->loadAll()->getOrder(),
            $this->system->email->loadOrderMailCount()->getOrder()
        );

        foreach ($data as $row)
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
     * Returns all e-mail-addresses for the given order-ids
     *
     * @param array $orderIds
     * @throws \Exception
     */
    public function getEmails(array $orderIds)
    {
        if (!is_array($orderIds) || empty($orderIds))
        {
            throw new \Exception("Array of order-ids expected", 400);
        }

        $data = $this->system->emails->loadAll()->getByOeid();
        foreach($orderIds as $orderId)
        {
            if (!isset($data[$orderId]))
            {
                throw new \Exception("Invalid orderId: $orderId");
            }

            print_r($row);

            //$return[] = $row[...]
        }
    }

    public function getEmailsConfigDesc(array $emails)
    {
        foreach ($emails as $row)
        {
            // TODO: Implement

        }
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