<?php

namespace Meanbee\Royalmail;

interface CarrierInterface
{

    /**
     * Get methods and rates based on package conditions
     *
     * @param $country_code
     * @param $package_value
     * @param $package_weight
     *
     * @return array
     */
    public function getRates($country_code, $package_value, $package_weight);
}
