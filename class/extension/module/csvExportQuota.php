<?php

namespace rpf\extension\module;
use rpf\system\module\log;

/**
 * Export all quotas + ssh accounts
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling
 * @link http://xing.doebeling.de
 * @link https://www.1601.com
 */
class csvExportQuota extends csvExport
{
    public function build($sort = true)
    {
        $quotas = $this // Plural of quota... quotas? quoten? what ever.
            ->getApi()
            ->getQuotaReadEntry()
            ->addReturnSsh()
            ->addReturnBase()
            ->getArray();

        if (!is_array($quotas))
        {
            log::warning('There are no ssh accounts you could export?!', __METHOD__);
        }
        else
        {
            foreach ($quotas as $quota)
            {
                if (isset($quota['ssh']) && !empty($quota['ssh']))
                {
                    foreach ($quota['ssh'] as $ssh)
                    {
                        $this->csv[] =
                            [
                                'Order Nr.' => empty($quota['ordnr']) ? '' : $quota['ordnr'],
                                'Path' => $quota['pfad'],
                                'Size' => $quota['quota'],
                                'Used' => $quota['used'],
                                'SSH Username' => @$ssh['name'],
                                'SSH Password' => $ssh['password'],
                            ];
                    }
                }
                else
                {
                    $this->csv[] =
                        [
                            'Order Nr.' => empty($quota['ordnr']) ? '' : $quota['ordnr'],
                            'Path' => $quota['pfad'],
                            'Size' => $quota['quota'],
                            'Used' => $quota['used'],
                            'SSH Username' => '',
                            'SSH Password' => '',
                        ];
                }
            }
        }
        return parent::build($sort);
    }

    public function execute($filename = 'QuotaAndSsh')
    {
        return parent::execute($filename);
    }
}