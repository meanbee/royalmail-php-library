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
 * Class Carrier
 * Provides methods to get rates from the csv files, interacts with the data class
 * to return arrays of methods.
 *
 * @category Meanbee
 * @author   Meanbee Limited <hello@meanbee.com>
 * @license  OSL v. 3.0
 * @link     http://github.com/meanbee/royalmail-php-library
 * @package  Meanbee\Royalmail
 */
class Carrier implements CarrierInterface
{

    /**
     * CSV file location for CountryCodes
     *
     * @var string
     */
    protected $csvCountryCodeDefault;

    /**
     * CSV file location for zone to methods
     *
     * @var string
     */
    protected $csvZoneToDeliveryMethodDefault;

    /**
     * CSV file location for method meta info
     *
     * @var string
     */
    protected $csvDeliveryMethodMetaDefault;

    /**
     * CSV file location for method to price
     *
     * @var string
     */
    protected $csvDeliveryToPriceDefault;

    /**
     * CSV file location for method codes to user-friendly label.
     *
     * @var string
     */
    protected $csvCleanNameToMethodDefault;

    /**
     * CSV file location for mapping of method to method group
     *
     * @var string
     */
    protected $csvCleanNameMethodGroupDefault;

    /**
     * Data resource class
     *
     * @var Data|null
     */
    protected $data;

    /**
     * Carrier constructor.
     *
     * @param string|null $csvCountryCode          - csv for Country Code
     * @param string|null $csvZoneToDeliveryMethod - csv for ZoneToDeliveyMethod
     * @param string|null $csvDeliveryMethodMeta   - csv for DeliveryMethodMeta
     * @param string|null $csvDeliveryToPrice      - csv for DeliveryToPrice
     * @param string|null $csvCleanNameToMethod    - csv for CleanNameToMethod
     * @param string|null $csvCleanNameMethodGroup - csv for $csvCleanNameMethodGroup
     */
    public function __construct(
        $csvCountryCode = null,
        $csvZoneToDeliveryMethod = null,
        $csvDeliveryMethodMeta = null,
        $csvDeliveryToPrice = null,
        $csvCleanNameToMethod = null,
        $csvCleanNameMethodGroup = null
    ) {
        $dir = dirname(realpath(__FILE__)) . '/';

        // Set the default csv values
        $this->csvCountryCodeDefault = "$dir../data/1_countryToZone.csv";
        if ($csvCountryCode) {
            $this->csvCountryCodeDefault = $csvCountryCode;
        }

        $this->csvZoneToDeliveryMethodDefault = "$dir../data/2_zoneToDeliveryMethod.csv";
        if ($csvZoneToDeliveryMethod) {
            $this->csvZoneToDeliveryMethodDefault = $csvZoneToDeliveryMethod;
        }

        $this->csvDeliveryMethodMetaDefault = "$dir../data/3_deliveryMethodMeta.csv";
        if ($csvDeliveryMethodMeta) {
            $this->csvDeliveryMethodMetaDefault = $csvDeliveryMethodMeta;
        }

        $this->csvDeliveryToPriceDefault = "$dir../data/4_deliveryToPrice.csv";
        if ($csvDeliveryToPrice) {
            $this->csvDeliveryToPriceDefault = $csvDeliveryToPrice;
        }

        $this->csvCleanNameToMethodDefault = "$dir../data/5_cleanNameToMethod.csv";
        if ($csvCleanNameToMethod) {
            $this->csvCleanNameToMethodDefault = $csvCleanNameToMethod;
        }

        $this->csvCleanNameMethodGroupDefault = "$dir../data/6_cleanNameMethodGroup.csv";
        if ($csvCleanNameMethodGroup) {
            $this->csvCleanNameMethodGroupDefault = $csvCleanNameMethodGroup;
        }

        $this->data = isset($data) ? $data : new Data(
            $this->csvCountryCodeDefault,
            $this->csvZoneToDeliveryMethodDefault,
            $this->csvDeliveryMethodMetaDefault,
            $this->csvDeliveryToPriceDefault,
            $this->csvCleanNameToMethodDefault,
            $this->csvCleanNameMethodGroupDefault
        );
    }

    /**
     * Method to run the appropriate sorting methods
     * in the correct order based on the country code,
     * package value, and package weight. Returns the
     * sorted values to the RoyalMailMethod class to be
     * converted into objects.
     *
     * The $ignore_package_value parameter allows for the
     * value of the packages to be ignored in the calculation
     * at the users discretion. This can be useful if the user
     * wants to get all available methods for the country code
     * and weight, ignoring the price of the package.
     *
     * @param string $country_code           - The country code being shipped to
     * @param int    $package_value          - The total package value
     * @param int    $package_weight         - The total package weight
     * @param bool   $ignore_package_value   - Flag to allow ignoring the
     *                                       package weight
     *
     * @return array                       - Array of all methods returned
     */
    public function getRates(
        $country_code,
        $package_value,
        $package_weight,
        $ignore_package_value = false
    ) {
        $sortedDeliveryMethods = [
            $this->data->calculateMethods(
                $country_code,
                $package_value,
                $package_weight,
                $ignore_package_value
            )
        ];

        $results = [];

        foreach ($sortedDeliveryMethods as $shippingMethod) {
            foreach ($shippingMethod as $item) {
                $method = new Method(
                    $item['shippingMethodName'],
                    $item['shippingMethodNameClean'],
                    $country_code,
                    $item['methodPrice'],
                    $item['insuranceValue'],
                    $item['minimumWeight'],
                    $item['maximumWeight'],
                    isset($item['size']) ? $item['size'] : null
                );

                $results[] = $method;
            }
        }
        return $results;
    }

    /**
     * Get full list of available RoyalMail Methods
     *
     * @return array
     */
    public function getAllMethods()
    {
        $methods = [];
        foreach ($this->data->getMappingCleanNameMethodGroup() as $item) {
            $methods[$item[0]] = $item[1];
        }

        return $methods;
    }

    /**
     * CSV file location for CountryCodes
     *
     * @return string - default csv
     */
    public function getCsvCountryCode()
    {
        return $this->csvCountryCodeDefault;
    }

    /**
     * CSV file location for zone to methods
     *
     * @return string - default csv
     */
    public function getCsvZoneToDeliveryMethod()
    {
        return $this->csvZoneToDeliveryMethodDefault;
    }

    /**
     * CSV file location for method meta info
     *
     * @return string - default csv
     */
    public function getCsvDeliveryMethodMeta()
    {
        return $this->csvDeliveryMethodMetaDefault;
    }

    /**
     * CSV file location for method to price
     *
     * @return string - default csv
     */
    public function getCsvDeliveryToPrice()
    {
        return $this->csvDeliveryToPriceDefault;
    }

    /**
     * CSV file location for method codes to user-friendly label.
     *
     * @return string - default csv
     */
    public function getCsvCleanNameToMethod()
    {
        return $this->csvCleanNameToMethodDefault;
    }

    /**
     * CSV file location for mapping of method to method group
     *
     * @return string - default csv
     */
    public function getCsvCleanNameMethodGroup()
    {
        return $this->csvCleanNameMethodGroupDefault;
    }
}
