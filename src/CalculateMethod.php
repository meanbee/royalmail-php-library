<?php namespace Meanbee\RoyalMail;

class CalculateMethod
{

    public $_csvCountryCode = '../data/1_countryToZone.csv';
    public $_csvZoneToDeliverMethod = '../data/2_zoneToDeliveryMethod.csv';
    public $_csvDeliveryMethodMeta = '../data/3_deliveryMethodMeta.csv';
    public $_csvDeliveryToPrice = '../data/4_deliveryToPrice.csv';

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
    public function getMethods($country_code, $package_value, $package_weight)
    {
        $data = new Data(
            $this->_csvCountryCode,
            $this->_csvZoneToDeliverMethod,
            $this->_csvDeliveryMethodMeta,
            $this->_csvDeliveryToPrice
        );

        $sortedDeliveryMethods = array($data->calculateMethods($country_code, $package_value, $package_weight));

        $results = [];

        foreach ($sortedDeliveryMethods as $shippingMethod) {
            foreach ($shippingMethod as $item) {
                    $method = new MethodInterface();
                    $method->countryCode = $country_code;
                    $method->shippingMethodName = $item['shippingMethodName'];
                    $method->minimumWeight = $item['minimumWeight'];
                    $method->maximumWeight = $item['maximumWeight'];
                    $method->methodPrice = $item['methodPrice'];
                    $method->insuranceValue = $item['insuranceValue'];
                    $method->shippingMethodNameClean = $item['shippingMethodNameClean'];

                    $results[] = $method;
            }
        }

        $results;
        return $results;
    }
}
