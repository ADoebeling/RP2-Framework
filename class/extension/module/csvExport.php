<?php

namespace rpf\extension\module;
use rpf\extension\extensionModule;
use rpf\system\module\exception;
use rpf\system\module\log;

/**
 * Class csvExport
 * @package rpf\extension\module
 */
class csvExport extends extensionModule
{
    /**
     * @var array $csv[$i]['title'] = $value
     */
    protected $csv = array();

    /**
     * @var string
     */
    protected $csvString = '';

    /**
     * Build csv string
     *
     * @param bool $sort
     * @return $this
     * @throws exception
     */
    protected function build($sort = true)
    {
        $data = array();
        foreach($this->csv as $key => $row)
        {
            // Headline
            if (empty($data))
            {
                foreach ($row as $rowTitle => $tmp)
                {
                    if (!isset($titleText))
                    {
                        $titleText = $rowTitle;
                    }
                    else
                    {
                        $titleText .= ";$rowTitle";
                    }
                }
            }

            // Content
            foreach ($row as $value)
            {
                if (!isset($data[$key]))
                {
                    $data[$key] = $value; //Strict
                }
                else
                {
                    $data[$key] .= ";$value";
                }
            }
        }
        if (empty($titleText) || empty($data))
        {
            throw new exception("No data for csv-export available");
        }
        else
        {
            if ($sort) {
                natcasesort($data);
            }
            $result = "$titleText\n";
            $result .= implode("\n", $data);
            $this->csvString = $result;
            return $this;
        }
    }

    /**
     * Return csv as string
     * @return string
     */
    public function getCsv()
    {
        if (empty($this->csvString)) $this->build();
        return $this->csvString;
    }


    /**
     * Send application/csv-header and starts downloading the csv
     *
     * @param string $filename
     * @return $this
     * @throws \Exception
     */
    public function sendCsvDownload($filename = 'export')
    {
        $filename .= '_'.date('ymd').'_1SRV.csv';
        log::info("Download started: $filename", __METHOD__);
        header('Content-Type: text/html; charset=iso-8859-15');
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=\"$filename\";");
        echo utf8_decode($this->getCsv());
        return $this;
    }

    public function execute($filename = 'export')
    {
        return $this
            ->build()
            ->sendCsvDownload($filename);
    }

    /**
     * Helper Function: Get customer-name formated
     *
     * @param string|array $firstNameOrArray
     * @param string $lastName
     * @param string $company
     * @return string string
     */
    public static function getCustomerNameFormatted($firstNameOrArray, $lastName = '', $company = '')
    {
        if (is_array($firstNameOrArray))
        {
            $company = $firstNameOrArray['cus_company'];
            $lastName = $firstNameOrArray['cus_last_name'];
            $firstNameOrArray = $firstNameOrArray['cus_first_name'];
        }
        $result = !empty($company) ? "$company ($firstNameOrArray $lastName)" : "$lastName, $firstNameOrArray";
        return $result != ', ' ? $result : '';
    }
}