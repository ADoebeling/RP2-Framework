<?php

namespace rpf\api\module;
use rpf\api\apiModule;
use rpf\system\module\log;

class bbRpc_getMessages extends apiModule
{
    /**
     * @link https://doku.premium-admin.eu/doku.php/api/methoden/bbrpc/getmessages
     * @param bool $deletePrevious
     * @param bool $addToLog
     * @return array
     * @throws \Exception
     */
    public function getMessages($deletePrevious = true, $addToLog = true)
    {
        $result = \bbRpc::getMessages(['delete_previous' => $deletePrevious]);

        if ($addToLog && !empty($result))
        {
            foreach($result as $row)
            {
                switch ($row['typ']) // Misspelled type
                {
                    case 'ok':
                        log::debug($row['msg'], __METHOD__);
                        break;
                    case 'info':
                        log::info($row['msg'], __METHOD__);
                        break;
                    case 'warn':
                        log::warning($row['msg'], __METHOD__);
                        throw new \Exception("RPC-Warning: ".$row['msg']);
                    case 'err':
                        log::error($row['msg'], __METHOD__);
                        throw new \Exception("RPC-Error: ".$row['msg']);
                    case 'hard':
                        log::error($row['msg'], __METHOD__);
                        throw new \Exception("RPC-Fatal-Error: ".$row['msg']);
                    default:
                        log::error("Unknown RPC-Message '".$row['msg']."' with type ".$row['typ'], __METHOD__);
                        throw new \Exception("Unknown RPC-Message '".$row['msg']."' with type ".$row['typ']);
                }
            }
        }
        return $result;
    }
}