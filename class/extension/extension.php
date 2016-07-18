<?php

namespace rpf\extension;
use rpf\extension\module\csvExportDomain;
use rpf\extension\module\csvExportMail4MailSaveEntry;
use rpf\extension\module\csvExportMysql;
use rpf\extension\module\csvExportQuota;
use rpf\extension\module\error;
use rpf\extension\module\index;
use rpf\extension\module\invoiceTextExport;
use rpf\extension\module\mgntRatioExport;
use rpf\system\module;

/**
 * RPF Extension-Class
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link https://xing-ad.1601.com
 * @link https://www.1601.com
 */
class extension extends module
{
    /**
     * @return csvExportDomain
     */
    public function getDomainExport()
    {
        return $this->getModule(csvExportDomain::class);
    }

    /**
     * @return csvExportMysql
     */
    public function getMysqlExport()
    {
        return $this->getModule(csvExportMysql::class);
    }

    /**
     * @return csvExportQuota
     */
    public function getQuotaExport()
    {
        return $this->getModule(csvExportQuota::class);
    }

    /**
     * @return index
     */
    public function getIndex()
    {
        return $this->getModule(index::class);
    }

    /**
     * @return invoiceTextExport
     */
    public function getInvoiceTextExport()
    {
        return $this->getModule(invoiceTextExport::class);
    }

    /**
     * @return mailExport
     */
    public function getMailExport()
    {
        return $this->getModule(mailExport::class);
    }

    /**
     * @return csvExportMail4MailSaveEntry
     */
    public function getMailExport4MailSaveEntry()
    { 
        return $this->getModule(csvExportMail4MailSaveEntry::class);
    }

    /**
     * @return error
     */
    public function getError()
    {
        return $this->getModule(error::class);
    }

    /**
     * @return mgntRatioExport
     */
    public function getMgntRatioExport()
    {
        return $this->getModule(mgntRatioExport::class);
    }
}