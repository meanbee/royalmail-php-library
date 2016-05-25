<?php
/**
 * Meanbee Royal Mail PHP Library
 *
 * PHP version 5.6
 *
 * @category  Meanbee
 * @package   Meanbee/royalmail-php-library
 * @author    Meanbee Limited <hello@meanbee.com>
 * @copyright 2016 Meanbee Limited (http://www.meanbee.com)
 * @license   OSL v. 3.0
 * @link      http://github.com/meanbee/royalmail-php-library
 */

namespace Meanbee\Royalmail;

/**
 * Interface CarrierInterface
 * Allows for a generic interface of carrier to account for any future carriers
 * to be added.
 *
 * @package Meanbee\Royalmail
 */
interface CarrierInterface
{

    /**
     * Get methods and rates based on package conditions
     *
     * @param string $country_code         - Country code package is going to
     * @param int    $package_value        - Package value
     * @param int    $package_weight       - Package weight
     * @param bool   $ignore_package_value - Whether to ignore package value or not
     *
     * @return array
     */
    public function getRates(
        $country_code,
        $package_value,
        $package_weight,
        $ignore_package_value = false
    );
}
