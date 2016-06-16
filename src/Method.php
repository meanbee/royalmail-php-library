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
 * Class Method
 * Object class to represent the method object and allow for
 * creation of method objects by the other classes.
 *
 * @category Meanbee
 * @package  Meanbee\Royalmail
 * @author   Meanbee Limited <hello@meanbee.com>
 * @license  OSL v. 3.0
 * @link     http://github.com/meanbee/royalmail-php-library
 */
class Method
{
    /**
     * The shipping code name of the method
     *
     * @var string
     */
    protected $code;

    /**
     * The clean shipping method name of the shipping method
     *
     * @var string
     */
    protected $name;

    /**
     * The country code of the method
     *
     * @var string
     */
    protected $countryCode;

    /**
     * Price of method
     *
     * @var string
     */
    protected $price;

    /**
     * Maximum value of package that is insured
     *
     * @var string
     */
    protected $insuranceValue;

    /**
     * The minimum weight the shipping method can accommodate
     *
     * @var string
     */
    protected $minimumWeight;

    /**
     * The maximum weight the shipping method can accommodate
     *
     * @var string
     */
    protected $maximumWeight;

    /**
     * The parcel size, only applies to small and medium parcels
     *
     * @var string
     */
    protected $size;

    /**
     * Method constructor.
     *
     * @param string $code           - Country code of method
     * @param string $name           - Clean shipping code of method
     * @param string $countryCode    - Country code of method
     * @param string $price          - Price of method
     * @param string $insuranceValue - Insurance value of method
     * @param string $minimumWeight  - Minimum weight the method can have
     * @param string $maximumWeight  - Maximum weight the method can have
     * @param null   $size           - Parcel size, only applies to sm and md parcels
     */
    public function __construct(
        $code,
        $name,
        $countryCode,
        $price,
        $insuranceValue,
        $minimumWeight,
        $maximumWeight,
        $size = null
    ) {
        $this->code = $code;
        $this->name = $name;
        $this->countryCode = $countryCode;
        $this->price = $price;
        $this->insuranceValue = $insuranceValue;
        $this->minimumWeight = $minimumWeight;
        $this->maximumWeight = $maximumWeight;
        $this->size = $size;
    }

    /**
     * The shipping code name of the method
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * The clean shipping method name of the shipping method
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The country code of the method
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Price of method
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Maximum value of package that is insured
     *
     * @return string
     */
    public function getInsuranceValue()
    {
        return $this->insuranceValue;
    }

    /**
     * The minimum weight the shipping method can accommodate
     *
     * @return string
     */
    public function getMinimumWeight()
    {
        return $this->minimumWeight;
    }

    /**
     * The maximum weight the shipping method can accommodate
     *
     * @return string
     */
    public function getMaximumWeight()
    {
        return $this->maximumWeight;
    }

    /**
     * The parcel size, only applies to small and medium parcels
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }
}
