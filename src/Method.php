<?php namespace Meanbee\Royalmail;

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

    public function __construct($code, $name, $countryCode, $price,
                                $insuranceValue, $minimumWeight, $maximumWeight, $size = null)
    {
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
