<?php

namespace rpf\system\module;

class log
{
    protected static function add($level, $desc, $context = NULL, $param = NULL)
    {
        $dir = __DIR__.'/../../../logs/';
        $file = $dir.'syslog_'.date("ymd").'_1SRV.log';

        $text = str_pad('['.date("r",time())."] [$level]", 41);
        if (RPF_DEBUG == true)
        {
            $context = strpos($context, ')') === false ? $context.'()' : $context;
            $text = str_pad("$text $context;", 120)."// $desc";

            if (\is_array($param) && count($param) == 1)
            {
                foreach ($param as $name => $value)
                {
                    $text .= " | $name: $value";
                }
            }
            elseif (is_array($param) || is_object($param))
            {
                //$text .= ' | '.json_encode($param);
                //$text .= "\n".print_r($param, 1)."\n";
            }
            elseif (!empty($param))
            {
                $text .= " | $param";
            }
        }
        else
        {
            $text .= $desc;
        }
        $text .= "\n";

        $fp = fopen($file, 'a');
        fwrite($fp, $text);
        fclose($fp);
        return true;
    }

    public static function debug($desc, $context, $array = NULL)
    {
        if (RPF_DEBUG == true)
        {
            return self::add(__FUNCTION__, $desc, $context, $array);
        }
        else
        {
            return true;
        }
    }

    public static function notice($desc, $context, $array = NULL)
    {
        return self::add(__FUNCTION__, $desc, $context, $array);
    }

    public static function info($desc, $context, $array = NULL)
    {
        return self::add(__FUNCTION__, $desc, $context, $array);
    }

    public static function warning($desc, $context, $array = NULL)
    {
        return self::add(__FUNCTION__, $desc, $context, $array);
    }

    public static function error($desc, $context, $array = NULL)
    {
        return self::add(__FUNCTION__, $desc, $context, $array);
    }
}