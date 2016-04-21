<?php

namespace rpf\extension;
use rpf\system\module;

/**
 * Model for all RP2-Extension-Modules
 *
 * @package system\extension
 */
class extensionModule extends module
{
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
        return !empty($company) ? "$company ($lastName, $firstNameOrArray)" : "$lastName, $firstNameOrArray";
    }
}