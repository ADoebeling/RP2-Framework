<?php

namespace rpf\api\module;
use rpf\api\apiModule;
use rpf\system\module\exception;
use rpf\system\module\log;

class bbRpc_setUrl extends apiModule
{
    public function setUrl($rpcUrl = false)
    {
        if ($rpcUrl !== false)
        {
            $this->rpcUrl = $rpcUrl;
            return \bbRpc::setUrl($rpcUrl);
        }
        else if ($this->rpcUrl !== false)
        {
            return true;
        }
        else {
            // Get the correct path on http AND ssh/bash
            $path = isset($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PWD'];
            preg_match("/\\/.*\\/(\\d*)_\\d*\\/.*/", $path, $matches);

            if (isset($matches[1]) && intval($matches[1]) > 1)
            {
                $this->setUrl(sprintf(RPF_API_MODULE_BBRPC_SETURL_PATTERN, $matches[1]));
                log::debug("Fetching rpcUrl from filesystem: {$this->rpcUrl}", __METHOD__);
                return true;
            } else {
                throw new exception("You're running a this script not at your df-server OR you have a realy old server-setup. Please set your RP2-Auftragsnummer in config. See: https://github.com/ADoebeling/RP2-Framework/issues/15");
            }
        }
    }

    public function getUrl()
    {
        return $this->rpcUrl;
    }
}