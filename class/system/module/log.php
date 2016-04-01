<?php

namespace www1601com\df_rp\system\module;

class log
{
    protected static function add($level, $desc, $param = NULL)
    {
        $dir = __DIR__.'/../../../logs/';
        $file = $dir.'syslog_'.date("ymd").'_1SRV.log';

        if (\is_array($param) && count($param) == 1)
        {
            foreach ($param as $name => $value) $param = " | $name: $value";
        }
        elseif (is_array($param) || is_object($param))
        {
            $param = ' | '.json_encode($param);
        }
        elseif (!empty($param))
        {
            $param = " | $param";
        }

        $text = '['.date("r",time())."] [$level] $desc $param\n";

        $fp = fopen($file, 'a');
        fwrite($fp, $text);
        fclose($fp);
    }

    public static function debug($desc, $array = NULL)
    {
        return self::add(__FUNCTION__, $desc, $array);
    }

    public static function notice($desc, $array = NULL)
    {
        return self::add(__FUNCTION__, $desc, $array);
    }

    public static function info($desc, $array = NULL)
    {
        return self::add(__FUNCTION__, $desc, $array);
    }

    public static function warning($desc, $array = NULL)
    {
        return self::add(__FUNCTION__, $desc, $array);
    }

    public static function error($desc, $array = NULL)
    {
        return self::add(__FUNCTION__, $desc, $array);
    }
}