<?php

namespace www1601com\df_rp;
use www1601com\df_rp\extension;
use www1601com\df_rp\system\module\log;

spl_autoload_register(function ($class)
{
    $class = str_replace([__NAMESPACE__, '\\'], [NULL, '/'] , __DIR__."/..$class.php");
    if (file_exists($class))
    {
        require_once $class;
        return true;
    }
    return false;
});

class system
{
    protected $log;

    public function __construct()
    {
        $this->log = new log();
        $this->log->debug('Start Session');
    }

    public function __destruct()
    {
        $this->log->debug("End Session\n\n\n");
    }
}

