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
 * Class Data
 * Data layer class. Interacts with the csv files and creates sorted mapped arrays
 * out of them. Provides methods to interact with the csv files and return sorted
 * method rates of several formats.
 *
 * @category Meanbee
 * @package  Meanbee\Royalmail
 * @author   Meanbee Limited <hello@meanbee.com>
 * @license  OSL v. 3.0
 * @link     http://github.com/meanbee/royalmail-php-library
 */
class Data
{

    // Constants to link to the appropriate columns in the CSV files
    const COUNTRY_CODE = 0;
    const WORLD_ZONE = 0;
    const SHIPPING_METHOD = 0;
    const METHOD_MIN_VALUE = 1;
    const METHOD_MAX_VALUE = 2;
    const METHOD_MIN_WEIGHT = 1;
    const METHOD_MAX_WEIGHT = 2;
    const METHOD_PRICE = 3;
    const METHOD_INSURANCE_VALUE = 4;
    const METHOD_NAME_CLEAN = 4;
    const METHOD_SIZE = 5;

    /**
     * Maps the clean name csv to the method method name csv
     *
     * @var array
     */
    protected $mappingCleanNameToMethod = [];

    /**
     * Maps the method group name to the clean name, to allow for printing
     * just the clean names to the user
     *
     * @var array
     */
    protected $mappingCleanNameMethodGroup = [];

    /**
     * Maps countries to zones.
     *
     * @var array
     */
    protected $mappingCountryToZone = [];

    /**
     * Maps zones to methods.
     *
     * @var array
     */
    protected $mappingZoneToMethod = [];

    /**
     * Map methods to meta information. This includes the insurance
     * amount, and the corresponding price levels
     *
     * @var array
     */
    protected $mappingMethodToMeta = [];

    /**
     * Maps method to prices (rates) based on weight boundaries
     *
     * @var array
     */
    protected $mappingDeliveryToPrice = [];

    /**
     * Data constructor.
     *
     * @param string $_csvCountryCode          - country code csv path
     * @param string $_csvZoneToDeliveryMethod - zone to method csv path
     * @param string $_csvDeliveryMethodMeta   - delivery method meta csv path
     * @param string $_csvDeliveryToPrice      - delivery to price csv path
     * @param string $_csvCleanNameToMethod    - clean name to method csv path
     * @param string $_csvCleanNameMethodGroup - clean name method group csv path
     */
    public function __construct(
        $_csvCountryCode,
        $_csvZoneToDeliveryMethod,
        $_csvDeliveryMethodMeta,
        $_csvDeliveryToPrice,
        $_csvCleanNameToMethod,
        $_csvCleanNameMethodGroup
    ) {
        $this->mappingCountryToZone = $this->_csvToArray($_csvCountryCode);
        $this->mappingZoneToMethod = $this->_csvToArray($_csvZoneToDeliveryMethod);
        $this->mappingMethodToMeta = $this->_csvToArray($_csvDeliveryMethodMeta);
        $this->mappingDeliveryToPrice = $this->_csvToArray($_csvDeliveryToPrice);
        $this->mappingCleanNameToMethod = $this->_csvToArray($_csvCleanNameToMethod);
        $this->mappingCleanNameMethodGroup = $this->_csvToArray(
            $_csvCleanNameMethodGroup
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
     * at the users discretion.
     *
     * @param string $country_code         - Country code being shipped to
     * @param int    $package_value        - Package value
     * @param int    $package_weight       - Package weight
     * @param bool   $ignore_package_value - ignore weight bool
     *
     * @return array
     */
    public function calculateMethods(
        $country_code,
        $package_value,
        $package_weight,
        $ignore_package_value = false
    ) {
        $sortedCountryCodeMethods = [
            $this->_getCountryCodeData(
                $country_code,
                $this->mappingCountryToZone
            )
        ];

        $sortedZoneToMethods = [
            $this->_getZoneToMethod(
                $sortedCountryCodeMethods,
                $this->mappingZoneToMethod
            )
        ];

        if ($ignore_package_value) {
            $sortedMethodToMeta = $this->_getMethodToMetaAll(
                $sortedZoneToMethods,
                $this->mappingMethodToMeta
            );
        } else {
            $sortedMethodToMeta = $this->_getMethodToMeta(
                $package_value,
                $sortedZoneToMethods,
                $this->mappingMethodToMeta
            );
        }

        return $this->_getMethodToPrice(
            $package_weight,
            $sortedMethodToMeta,
            $this->mappingDeliveryToPrice
        );
    }

    /**
     * Method to return a 2d array of world zones a country
     * (by its country code) is located in.
     *
     * @param string $country_code         - Country code to filter on
     * @param array  $mappingCountryToZone - Array for mapped countries to zones
     *
     * @return array
     */
    private function _getCountryCodeData($country_code, $mappingCountryToZone)
    {
        // Get All array items that match the country code
        $countryCodeData = [];
        foreach ($mappingCountryToZone as $item) {
            if (isset($item[self::COUNTRY_CODE])
                && $item[self::COUNTRY_CODE] == $country_code
            ) {
                foreach ($item as $keys) {
                    $countryCodeData[] = $keys;
                }
            }
        }

        // Clean up the array removing excess values
        foreach ($countryCodeData as $key => $value) {
            if ($value == $country_code) {
                unset($countryCodeData[$key]);
            }
        }
        $countryCodeData = array_values($countryCodeData);

        return $countryCodeData;
    }

    /**
     * Method to return a 2d array of possible delivery methods based
     * on the given world zones a country is in.
     *
     * @param array $sortedCountryCodeMethods - Methods to filter on
     * @param array $mappingZoneToMethod      - ZonesToMethods to filter on
     *
     * @return array
     */
    private function _getZoneToMethod(
        $sortedCountryCodeMethods,
        $mappingZoneToMethod
    ) {
        $mappingZoneData = [];
        foreach ($sortedCountryCodeMethods as $key => $value) {
            foreach ($value as $zone) {
                foreach ($mappingZoneToMethod as $item) {
                    if (isset($item[self::WORLD_ZONE])
                        && $item[self::WORLD_ZONE] == $zone
                    ) {
                        foreach ($item as $keys) {
                            $mappingZoneData[] = $keys;
                        }
                    }
                }
            }
        }

        // Clean up the array removing excess values
        foreach ($sortedCountryCodeMethods as $item => $itemValue) {
            foreach ($itemValue as $zone) {
                foreach ($mappingZoneData as $key => $value) {
                    if ($value == $zone) {
                        unset($mappingZoneData[$key]);
                    }
                }
            }
        }

        $mappingZoneData = array_values($mappingZoneData);

        return $mappingZoneData;
    }

    /**
     * Method to return a 2d array of the meta data for each
     * given allowed shipping method and the given package
     * value.
     *
     * @param int   $packageValue        - Package value to filter methods on
     * @param array $sortedZoneToMethods - SortedZoneToMethods to filter with
     * @param array $mappingMethodToMeta - MethodToMeta to filter on
     *
     * @return array
     */
    private function _getMethodToMeta(
        $packageValue,
        $sortedZoneToMethods,
        $mappingMethodToMeta
    ) {
        $mappingZoneMethodData = array();
        foreach ($sortedZoneToMethods as $key => $value) {
            foreach ($value as $method) {
                foreach ($mappingMethodToMeta as $item) {
                    if (isset($item[self::SHIPPING_METHOD])
                        && $item[self::SHIPPING_METHOD] == $method
                    ) {
                        if ($packageValue >= $item[self::METHOD_MIN_VALUE]
                            && $packageValue <= $item[self::METHOD_MAX_VALUE]
                        ) {
                            $mappingZoneMethodData[$item[0]] = $item;
                        }
                    }
                }
            }
        }

        return $mappingZoneMethodData;
    }

    /**
     * Method to return a 2d array of sorted shipping methods based on
     * the weight of the item and the allowed shipping methods. Returns
     * a 2d array to be converting into objects by the RoyalMailMethod
     * class. Also adds the pretty text from the meta table to the
     * correct shipping method, to allow for less text in the delivery
     * to price csv.
     *
     * @param int   $package_weight         - The weight of the package
     * @param array $sortedMethodToMeta     - Sorted methods to meta
     * @param array $mappingDeliveryToPrice - Sorted delivery to price
     *
     * @return array
     */
    private function _getMethodToPrice(
        $package_weight,
        $sortedMethodToMeta,
        $mappingDeliveryToPrice
    ) {
        $mappingDeliveryToPriceData = [];
        foreach ($mappingDeliveryToPrice as $item) {
            if (isset($item[self::SHIPPING_METHOD])
                && isset($sortedMethodToMeta[$item[self::SHIPPING_METHOD]])
                && $package_weight >= $item[self::METHOD_MIN_WEIGHT]
                && $package_weight <= $item[self::METHOD_MAX_WEIGHT]
            ) {
                $data = $sortedMethodToMeta[$item[self::SHIPPING_METHOD]];
                $resultArray = [
                    'shippingMethodName' => $item[self::SHIPPING_METHOD],
                    'minimumWeight' => (double) $item[self::METHOD_MIN_WEIGHT],
                    'maximumWeight' => (double) $item[self::METHOD_MAX_WEIGHT],
                    'methodPrice' => (double) $item[self::METHOD_PRICE],
                    'insuranceValue' => (int) $item[self::METHOD_INSURANCE_VALUE],
                    'shippingMethodNameClean' => $data[self::METHOD_NAME_CLEAN]
                ];

                if (isset($item[self::METHOD_SIZE])) {
                    $resultArray['size'] = $item[self::METHOD_SIZE];
                }

                $mappingDeliveryToPriceData[] = $resultArray;
            }
        }

        return $mappingDeliveryToPriceData;
    }

    /**
     * Method to return a 2d array of the meta data for each
     * given allowed shipping method, not based on the price
     * of the item. Returns all possible available methods
     * that are available.
     *
     * @param array $sortedZoneToMethods - Sorted array of zone to methods
     * @param array $mappingMethodToMeta - Sorted array of methods to the meta
     *
     * @return array
     */
    private function _getMethodToMetaAll($sortedZoneToMethods, $mappingMethodToMeta)
    {
        $mappingZoneMethodData = array();
        foreach ($sortedZoneToMethods as $key => $value) {
            foreach ($value as $method) {
                foreach ($mappingMethodToMeta as $item) {
                    if (isset($item[self::SHIPPING_METHOD])
                        && $item[self::SHIPPING_METHOD] == $method
                    ) {
                        $mappingZoneMethodData[$item[0]] = $item;
                    }
                }
            }
        }

        return $mappingZoneMethodData;
    }

    /**
     * Reads the given csv in to a 2d array
     *
     * @param string $filename  - The filename of the csv
     * @param string $delimiter - The delimiter
     *
     * @return array
     * @throws \Exception
     */
    private function _csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new \Exception(
                "Unable to load the Royal Mail price data csv for
                '$filename'. Ensure that the data folder contains all the necessary
                 csvs."
            );
        }

        $header = null;
        $data = [];
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }

    /**
     * Maps the method group name to the clean name and the related method
     *
     * @return array
     */
    public function getMappingCleanNameToMethod()
    {
        return $this->mappingCleanNameToMethod;
    }

    /**
     * Maps the method group name to the clean name, to allow
     * for printing just the clean names to the user
     *
     * @return array
     */
    public function getMappingCleanNameMethodGroup()
    {
        return $this->mappingCleanNameMethodGroup;
    }

    /**
     * Maps countries to zones.
     *
     * @return array
     */
    public function getMappingCountryToZone()
    {
        return $this->mappingCountryToZone;
    }

    /**
     * Maps zones to methods.
     *
     * @return array
     */
    public function getMappingZoneToMethod()
    {
        return $this->mappingZoneToMethod;
    }

    /**
     * Map methods to meta information. This includes the insurance
     * amount, and the corresponding price levels
     *
     * @return array
     */
    public function getMappingMethodToMeta()
    {
        return $this->mappingMethodToMeta;
    }

    /**
     * Maps method to prices (rates) based on weight boundaries
     *
     * @return array
     */
    public function getMappingDeliveryToPrice()
    {
        return $this->mappingDeliveryToPrice;
    }
}
