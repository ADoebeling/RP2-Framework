<?php

namespace rpf;
use rpf\system\module\exception;
use rpf\system\module\log;

/**
 * Report all errors
 */
error_reporting(E_ALL);

/**
 * Location to store the bbRpc-Cookie
 */
define('BBRPC_COOKIE', '/tmp/RPF_bbRpc_cookie.txt');

/**
 * Debug-Mode?
 */
define('RPF_DEBUG', true);

/**
 * Let us use a autoloader for our classes.
 * (cause nobody likes require_once)
 *
 * @param $class
 * @return bool
 * @throws \Exception
 */
function classLoader ($class)
{
    $path = explode('\\', $class);
    unset($path[0]); //Namespace 'rpf'
    $file = __DIR__ . '/class';
    foreach ($path as $name) {
        $file .= "/$name";
    }
    $file .= '.php';

    if (file_exists($file)) {
        require_once $file;
        $logFileName = str_replace(__DIR__, '', $file);
        log::debug("Include $logFileName", __FUNCTION__ . "('$class')");
        return true;
    } else {
        log::error("Couldn't  load class from '$file'", __FUNCTION__."($class)");
        echo "<pre>";
        throw new \Exception("File $file not found");
        return false;
    }
}
spl_autoload_register('\rpf\classLoader');

/**
 * @param $title
 * @param $text
 * @param string $code
 * @todo move to extension/error
 */
function showError($title, $text, $code = '')
{
    ?>
        <head>
            <style>
                body
                {
                    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                }

                #errorLayer
                {
                    background: white url("/extension/static/sadMumby.jpg")no-repeat center center fixed;
                    -webkit-background-size: cover;
                    -moz-background-size: cover;
                    -o-background-size: cover;
                    background-size: cover;
                    z-index: 20;
                    height: 100%;
                    width: 100%;
                    background-repeat:no-repeat;
                    background-position:center;
                    position:absolute;
                    top: 0px;
                    left: 0px;
                }

                #error
                {
                    position: absolute;
                    top: 0px;
                    left: 0px;
                    margin: 30px;
                    padding: 30px;
                    width: 350px;
                    word-wrap:break-word;
                    color: white;
                    background: black;
                    opacity: 0.5;
                }

                #error h1
                {
                    font-size: 1.5em;
                    margin-top:0;
                }

                #error pre
                {
                    height: 300px;
                    overflow: auto;
                }


            </style>
        </head>
    <body>
        <div id="errorLayer">
            <div id="error">
                <h1><?=$title?></h1>
                <?=$text?>
            </div>
        </div>
    </body>
    <?php
}



/**
 * @param \Exception $exception
 * @return bool
 */
function exceptionHandler(\Exception $e)
{
    showError('Sorry, the script died with a exception', $e->getMessage(), $e->getCode());
    log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine(), __FUNCTION__,$e->getTrace());
    die('-die-');
}
set_exception_handler('rpf\exceptionHandler');

//throw new exception('yow!', 200);


/**
 * Lets pass all php-errors to our syslog
 *
 * @param $errorNumber
 * @param $errorString
 * @param $errorFile
 * @param $errorLine
 * @return bool
 */
function errorHandler($errorNumber, $errorString, $errorFile, $errorLine)
{
    showError("Sorry, the script died with a PHP-Error", $errorString.' in '.$errorFile.':'.$errorLine);
    log::error("PHP-ERROR\n\n!!!!! $errorString\n!!!!! $errorFile:$errorLine\n", __METHOD__, debug_backtrace());
    die();
}
set_error_handler('rpf\errorHandler');

