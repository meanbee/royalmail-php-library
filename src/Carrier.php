<?php namespace Meanbee\Royalmail;

class Carrier implements CarrierInterface
{

    // Helper document root
    public $documentRoot;

    /**
     * CSV file location for CountryCodes
     * @var string
     */
    protected $_csvCountryCode;

    /**
     * CSV file location for zone to methods
     *
     * @var string
     */
    protected $_csvZoneToDeliveryMethod;

    /**
     * CSV file location for method meta info
     *
     * @var string
     */
    protected $_csvDeliveryMethodMeta;

    /**
     * CSV file location for method to price
     *
     * @var string
     */
    protected $_csvDeliveryToPrice;

    /**
     * CSV file location for method codes to user-friendly label.
     *
     * @var string
     */
    protected $_csvCleanNameToMethod;

    /**
     * CSV file location for mapping of method to method group
     *
     * @var string
     */
    protected $_csvCleanNameMethodGroup;


    /**
     * Data resource class
     *
     * @var Data|null
     */
    protected $data;

    /**
     * CalculateMethod constructor.
     * @param null|Data $data
     */
    public function __construct($data = null)
    {
        $this->getDocumentRoot();

        // Set the default csv values
        $this->_csvCountryCode = $this->documentRoot . '../data/1_countryToZone.csv';
        $this->_csvZoneToDeliveryMethod = $this->documentRoot . '../data/2_zoneToDeliveryMethod.csv';
        $this->_csvDeliveryMethodMeta = $this->documentRoot . '../data/3_deliveryMethodMeta.csv';
        $this->_csvDeliveryToPrice = $this->documentRoot . '../data/4_deliveryToPrice.csv';
        $this->_csvCleanNameToMethod = $this->documentRoot . '../data/5_cleanNameToMethod.csv';
        $this->_csvCleanNameMethodGroup = $this->documentRoot . '../data/6_cleanNameMethodGroup.csv';

        $this->data = isset($data) ? $data : new Data(
            $this->_csvCountryCode,
            $this->_csvZoneToDeliveryMethod,
            $this->_csvDeliveryMethodMeta,
            $this->_csvDeliveryToPrice,
            $this->_csvCleanNameToMethod,
            $this->_csvCleanNameMethodGroup
        );
    }

    /**
     * Method to run the appropriate sorting methods
     * in the correct order based on the country code,
     * package value, and package weight. Returns the
     * sorted values to the RoyalMailMethod class to be
     * converted into objects.
     *
     * @param $country_code
     * @param $package_value
     * @param $package_weight
     *
     * @return array
     */
    public function getRates($country_code, $package_value, $package_weight)
    {

        $sortedDeliveryMethods = [$this->data->calculateMethods($country_code, $package_value, $package_weight)];

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
     * Helper function to get the document root for csv files
     */
    public function getDocumentRoot()
    {
        $this->documentRoot = dirname(realpath(__FILE__)) . '/';
    }

    /**
     * CSV file location for CountryCodes
     *
     * @return string
     */
    public function getCsvCountryCode()
    {
        return $this->_csvCountryCode;
    }

    /**
     * CSV file location for zone to methods
     *
     * @return string
     */
    public function getCsvZoneToDeliveryMethod()
    {
        return $this->_csvZoneToDeliveryMethod;
    }

    /**
     * CSV file location for method meta info
     *
     * @return string
     */
    public function getCsvDeliveryMethodMeta()
    {
        return $this->_csvDeliveryMethodMeta;
    }

    /**
     * CSV file location for method to price
     *
     * @return string
     */
    public function getCsvDeliveryToPrice()
    {
        return $this->_csvDeliveryToPrice;
    }

    /**
     * CSV file location for method codes to user-friendly label.
     *
     * @return string
     */
    public function getCsvCleanNameToMethod()
    {
        return $this->_csvCleanNameToMethod;
    }

    /**
     * CSV file location for mapping of method to method group
     *
     * @return string
     */
    public function getCsvCleanNameMethodGroup()
    {
        return $this->_csvCleanNameMethodGroup;
    }
}
