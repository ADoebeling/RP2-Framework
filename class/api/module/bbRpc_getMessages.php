<?php

namespace rpf\api\module;
use rpf\api\apiModule;

class bbRpc_getMessages extends apiModule
{
    public function getMessages($deletePrevious = 1)
    {
        $enumRpcMessageLevels = array (
            'ok',    // Erfolg
            'info',  // Notiz
            'warn',  // Warnung
            'err',   // Fehler
            'hard',  // Harter Fehler: weitere Skriptausführung unmöglich
        );

        return \bbRpc::getMessages($deletePrevious);
    }

}