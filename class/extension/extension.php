<?php

namespace www1601com\df_rp\extension;
use www1601com\df_rp\api;

require_once __DIR__ . '/../api/api.php';
require_once __DIR__.'/module/www1601com_mailExport/mailExport.php';
require_once __DIR__.'/module/www1601com_mgntRatioExport/mgntRatioExport.php';
require_once __DIR__.'/module/www1601com_invoiceTextExport/invoiceTextExport.php';



/**
 * Wrapper-Class for all rp2-extensions
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/df_rp2
 * @link http://github.com/ADoebeling
 * @link http://xing-ad.1601.com
 * @package www1601com\df_rp\extension
 */
class extension extends api {

    /**
     * @var object mailExport
     */
    public $mailExport;

    /**
     * @var object mgntRatioExport
     */
    public $mgntRatioExport;

    /**
     * @var object invoiceTextExport
     */
    public $invoiceTextExport;


    /**
     * Builds the class-structure
     */
    public function __construct()
    {
        parent::__construct();
        $this->mailExport = new mailExport($this);
        $this->mgntRatioExport = new mgntRatioExport($this);
        $this->invoiceTextExport = new invoiceTextExport($this);
    }


    /**
     * AutoLoad-Class
     *
     * @param $moduleName
     * @todo Implement
     */
    static function includeModules($moduleName)
    {
        $moduleName = str_replace(__CLASS__.'\\', '', $moduleName);

        //if (!require_once __DIR__."/module/$moduleName.php")
        //{
        //    throw new \Exception("Modul '$moduleName' doesn't exists", 404);
        //}
    }
}

spl_autoload_register(__NAMESPACE__ .'\extension::includeModules');