<?php namespace Meanbee\Royalmail;

class RoyalmailTest extends \PHPUnit_Framework_TestCase
{

    const METHOD_NAME_ROW_META_CSV = 0;
    const METHOD_NAME_ROW_CLEANNAME_CSV = 2;
    const METHOD_NAME_ROW_PRICE_CSV = 0;
    const METHOD_CLEAN_NAME_ROW_META_CSV = 4;
    const METHOD_CLEAN_NAME_ROW_CLEANNAME_CSV = 1;
    const METHOD_CLEAN_NAME_ROW_CLEANNAMEGROUP_CSV = 1;
    const METHOD_CLEAN_NAME_GROUP_CLEANNAME_CSV = 0;
    const METHOD_CLEAN_NAME_GROUP_CLEANNAMEGROUP_CSV = 0;
    const INSURANCE_ROW_META_CSV = 3;
    const INSURANCE_ROW_PRICE_CSV = 4;
    const INSURANCE_ROW_CLEANNAME_CSV = 5;

    /** @var CalculateMethod $calculateMethodClass */
    private $calculateMethodClass;

    /** @var Data $dataClass */
    private $dataClass;
    private $emptyArray;
    private $testDataClassArray;

    /**
     * Setup the necessary classes and data
     */
    public function setUp()
    {
        /** @var CalculateMethod */
        $this->calculateMethodClass = new CalculateMethod();
        $this->dataClass = new Data(
            $this->calculateMethodClass->_csvCountryCode,
            $this->calculateMethodClass->_csvZoneToDeliveryMethod,
            $this->calculateMethodClass->_csvDeliveryMethodMeta,
            $this->calculateMethodClass->_csvDeliveryToPrice,
            $this->calculateMethodClass->_csvCleanNameToMethod,
            $this->calculateMethodClass->_csvCleanNameMethodGroup
        );

        $this->emptyArray = [];
        $this->testDataClassArray = array(
            0  =>
                array(
                    'shippingMethodName'      => 'UK_GUARANTEED_ROYAL_MAIL_SPECIAL_DELIVERY_1PM_500',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '0.100',
                    'methodPrice'             => '6.45',
                    'insuranceValue'          => '500',
                    'shippingMethodNameClean' => 'Special Delivery: Guaranteed by 1pm',
                    'size'                    => '',
                ),
            1  =>
                array(
                    'shippingMethodName'      => 'UK_GUARANTEED_ROYAL_MAIL_SPECIAL_DELIVERY_9AM_50',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '0.100',
                    'methodPrice'             => '18.36',
                    'insuranceValue'          => '50',
                    'shippingMethodNameClean' => 'Special Delivery: Guaranteed by 9am',
                    'size'                    => '',
                ),
            2  =>
                array(
                    'shippingMethodName'      => 'UK_GUARANTEED_ROYAL_MAIL_SPECIAL_DELIVERY_1PM_SATURDAY_500',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '0.100',
                    'methodPrice'             => '10.74',
                    'insuranceValue'          => '500',
                    'shippingMethodNameClean' => 'Special Delivery: Guaranteed by 1pm Saturday',
                    'size'                    => '',
                ),
            3  =>
                array(
                    'shippingMethodName'      => 'UK_GUARANTEED_ROYAL_MAIL_SPECIAL_DELIVERY_9AM_SATURDAY_50',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '0.100',
                    'methodPrice'             => '21.36',
                    'insuranceValue'          => '50',
                    'shippingMethodNameClean' => 'Special Delivery: Guaranteed by 9am Saturday',
                    'size'                    => '',
                ),
            4  =>
                array(
                    'shippingMethodName'      => 'UK_STANDARD_FIRST_CLASS_LETTER',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '0.100',
                    'methodPrice'             => '0.63',
                    'insuranceValue'          => '20',
                    'shippingMethodNameClean' => 'Standard First Class Letter',
                    'size'                    => '',
                ),
            5  =>
                array(
                    'shippingMethodName'      => 'UK_STANDARD_FIRST_CLASS_LARGE_LETTER',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '0.100',
                    'methodPrice'             => '0.95',
                    'insuranceValue'          => '20',
                    'shippingMethodNameClean' => 'Standard First Class Large Letter',
                    'size'                    => '',
                ),
            6  =>
                array(
                    'shippingMethodName'      => 'UK_STANDARD_FIRST_CLASS_SMALL_PARCEL',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '1.000',
                    'methodPrice'             => '3.30',
                    'insuranceValue'          => '20',
                    'shippingMethodNameClean' => 'Standard First Class Small Parcel',
                    'size'                    => 'SMALL',
                ),
            7  =>
                array(
                    'shippingMethodName'      => 'UK_STANDARD_FIRST_CLASS_MEDIUM_PARCEL',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '1.000',
                    'methodPrice'             => '5.65',
                    'insuranceValue'          => '20',
                    'shippingMethodNameClean' => 'Standard First Class Medium Parcel',
                    'size'                    => 'MEDIUM',
                ),
            8  =>
                array(
                    'shippingMethodName'      => 'UK_STANDARD_SECOND_CLASS_LETTER',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '0.100',
                    'methodPrice'             => '0.54',
                    'insuranceValue'          => '20',
                    'shippingMethodNameClean' => 'Second Class: Letter',
                    'size'                    => '',
                ),
            9  =>
                array(
                    'shippingMethodName'      => 'UK_STANDARD_SECOND_CLASS_LARGE_LETTER',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '0.100',
                    'methodPrice'             => '0.74',
                    'insuranceValue'          => '20',
                    'shippingMethodNameClean' => 'Second Class: Large Letter',
                    'size'                    => '',
                ),
            10 =>
                array(
                    'shippingMethodName'      => 'UK_STANDARD_SECOND_CLASS_SMALL_PARCEL',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '1.000',
                    'methodPrice'             => '2.80',
                    'insuranceValue'          => '20',
                    'shippingMethodNameClean' => 'Second Class: Small Parcel',
                    'size'                    => 'SMALL',
                ),
            11 =>
                array(
                    'shippingMethodName'      => 'UK_STANDARD_SECOND_CLASS_MEDIUM_PARCEL',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '1.000',
                    'methodPrice'             => '4.89',
                    'insuranceValue'          => '20',
                    'shippingMethodNameClean' => 'Second Class: Medium Parcel',
                    'size'                    => 'MEDIUM',
                ),
            12 =>
                array(
                    'shippingMethodName'      => 'UK_CONFIRMED_ROYAL_MAIL_SIGNED_FOR_FIRST_CLASS_LETTER',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '0.100',
                    'methodPrice'             => '1.73',
                    'insuranceValue'          => '50',
                    'shippingMethodNameClean' => 'Signed For: First Class Letter',
                    'size'                    => '',
                ),
            13 =>
                array(
                    'shippingMethodName'      => 'UK_CONFIRMED_ROYAL_MAIL_SIGNED_FOR_FIRST_CLASS_LARGE_LETTER',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '0.100',
                    'methodPrice'             => '2.05',
                    'insuranceValue'          => '50',
                    'shippingMethodNameClean' => 'Signed For: First Class Large Letter',
                    'size'                    => '',
                ),
            14 =>
                array(
                    'shippingMethodName'      => 'UK_CONFIRMED_ROYAL_MAIL_SIGNED_FOR_FIRST_CLASS_SMALL_PARCEL',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '1.000',
                    'methodPrice'             => '4.40',
                    'insuranceValue'          => '50',
                    'shippingMethodNameClean' => 'Signed For: First Class Small Parcel',
                    'size'                    => 'SMALL',
                ),
            15 =>
                array(
                    'shippingMethodName'      => 'UK_CONFIRMED_ROYAL_MAIL_SIGNED_FOR_FIRST_CLASS_MEDIUM_PARCEL',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '1.000',
                    'methodPrice'             => '6.75',
                    'insuranceValue'          => '50',
                    'shippingMethodNameClean' => 'Signed For: First Class Medium Parcel',
                    'size'                    => 'MEDIUM',
                ),
            16 =>
                array(
                    'shippingMethodName'      => 'UK_CONFIRMED_ROYAL_MAIL_SIGNED_FOR_SECOND_CLASS_LETTER',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '0.100',
                    'methodPrice'             => '1.64',
                    'insuranceValue'          => '50',
                    'shippingMethodNameClean' => 'Signed For: Second Class Class Letter',
                    'size'                    => '',
                ),
            17 =>
                array(
                    'shippingMethodName'      => 'UK_CONFIRMED_ROYAL_MAIL_SIGNED_FOR_SECOND_CLASS_LARGE_LETTER',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '0.100',
                    'methodPrice'             => '1.84',
                    'insuranceValue'          => '50',
                    'shippingMethodNameClean' => 'Signed For: Second Class Class Large Letter',
                    'size'                    => '',
                ),
            18 =>
                array(
                    'shippingMethodName'      => 'UK_CONFIRMED_ROYAL_MAIL_SIGNED_FOR_SECOND_CLASS_SMALL_PARCEL',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '1.000',
                    'methodPrice'             => '3.90',
                    'insuranceValue'          => '50',
                    'shippingMethodNameClean' => 'Signed For: Second Class Small Parcel',
                    'size'                    => 'SMALL',
                ),
            19 =>
                array(
                    'shippingMethodName'      => 'UK_CONFIRMED_ROYAL_MAIL_SIGNED_FOR_SECOND_CLASS_MEDIUM_PARCEL',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '1.000',
                    'methodPrice'             => '5.99',
                    'insuranceValue'          => '50',
                    'shippingMethodNameClean' => 'Signed For: Second Class Medium Parcel',
                    'size'                    => 'MEDIUM',
                ),
            20 =>
                array(
                    'shippingMethodName'      => 'PARCELFORCE_WORLDWIDE_EXPRESS_9',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '2.000',
                    'methodPrice'             => '39.48',
                    'insuranceValue'          => '200',
                    'shippingMethodNameClean' => 'Parcelforce Worldwide: Express 9',
                    'size'                    => '',
                ),
            21 =>
                array(
                    'shippingMethodName'      => 'PARCELFORCE_WORLDWIDE_EXPRESS_10',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '2.000',
                    'methodPrice'             => '29.46',
                    'insuranceValue'          => '200',
                    'shippingMethodNameClean' => 'Parcelforce Worldwide: Express 10',
                    'size'                    => '',
                ),
            22 =>
                array(
                    'shippingMethodName'      => 'PARCELFORCE_WORLDWIDE_EXPRESS_AM',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '2.000',
                    'methodPrice'             => '19.49',
                    'insuranceValue'          => '200',
                    'shippingMethodNameClean' => 'Parcelforce Worldwide: Express AM',
                    'size'                    => '',
                ),
            23 =>
                array(
                    'shippingMethodName'      => 'PARCELFORCE_WORLDWIDE_EXPRESS_24',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '2.000',
                    'methodPrice'             => '16.49',
                    'insuranceValue'          => '100',
                    'shippingMethodNameClean' => 'Parcelforce Worldwide: Express 24',
                    'size'                    => '',
                ),
            24 =>
                array(
                    'shippingMethodName'      => 'PARCELFORCE_WORLDWIDE_EXPRESS_48',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '2.000',
                    'methodPrice'             => '11.99',
                    'insuranceValue'          => '100',
                    'shippingMethodNameClean' => 'Parcelforce Worldwide: Express 48',
                    'size'                    => '',
                ),
            25 =>
                array(
                    'shippingMethodName'      => 'PARCELFORCE_WORLDWIDE_EXPRESS_9_SATURDAY',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '2.000',
                    'methodPrice'             => '48.48',
                    'insuranceValue'          => '200',
                    'shippingMethodNameClean' => 'Parcelforce Worldwide: Express 9 Saturday',
                    'size'                    => '',
                ),
            26 =>
                array(
                    'shippingMethodName'      => 'PARCELFORCE_WORLDWIDE_EXPRESS_10_SATURDAY',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '2.000',
                    'methodPrice'             => '38.48',
                    'insuranceValue'          => '200',
                    'shippingMethodNameClean' => 'Parcelforce Worldwide: Express 10 Saturday',
                    'size'                    => '',
                ),
            27 =>
                array(
                    'shippingMethodName'      => 'PARCELFORCE_WORLDWIDE_EXPRESS_AM_SATURDAY',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '2.000',
                    'methodPrice'             => '28.49',
                    'insuranceValue'          => '200',
                    'shippingMethodNameClean' => 'Parcelforce Worldwide: Express AM Saturday',
                    'size'                    => '',
                ),
            28 =>
                array(
                    'shippingMethodName'      => 'PARCELFORCE_WORLDWIDE_EXPRESS_24_SATURDAY',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '2.000',
                    'methodPrice'             => '25.49',
                    'insuranceValue'          => '100',
                    'shippingMethodNameClean' => 'Parcelforce Worldwide: Express 24 Saturday',
                    'size'                    => '',
                ),
            29 =>
                array(
                    'shippingMethodName'      => 'PARCELFORCE_WORLDWIDE_EXPRESS_48_SATURDAY',
                    'minimumWeight'           => '0.001',
                    'maximumWeight'           => '2.000',
                    'methodPrice'             => '21.99',
                    'insuranceValue'          => '100',
                    'shippingMethodNameClean' => 'Parcelforce Worldwide: Express 48 Saturday',
                    'size'                    => '',
                ),
        );
    }

    /**
     * Cleans up the used classes
     */
    public function tearDown()
    {
        $this->calculateMethodClass = null;
        $this->dataClass = null;
    }

    /**
     * Test to ensure that the calculate method class is returning
     */
    public function testRoyalmailClassRealValues()
    {
        $this->assertNotEmpty($this->calculateMethodClass->getMethods('GB', 20, 0.050));
    }

    /**
     * Test to compare the returned data from the Data class to expected values
     */
    public function testRoyalmailMethodRealValues()
    {
        $this->assertEquals($this->testDataClassArray, $this->dataClass->calculateMethods('GB', 19.99, 0.050));
    }

    /**
     * Test to ensure the only the expected empty array is returned from incorrect data to the data class
     */
    public function testRoyalmailMethodFake()
    {
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods('GASD', "aSDASD", "ASDASD"));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods(123123123, "asdasd", "asdadasd"));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods(123123, 123123, "ASDASD"));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods(123123123, 123123123, 123123123));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods('GB', "aSD!!ASD", 0.100));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods('GB', 123123123123, 0.100));
    }

    /**
     * Test to ensure that only the expected empty array is returned from null and incorrect data
     * from the Data class
     */
    public function testRoyalmailMethodNull()
    {
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods(null, 123123123123, 0.100));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods(null, null, 0.100));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods('GB', null, 0.100));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods('GB', null, null));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods('GB', 123123123123, null));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods(null, null, null));
    }

    /**
     * Test to ensure that only the expected empty array is returned from incorrect
     * data from the CalculateMethod class
     */
    public function testRoyalmailClassFake()
    {
        $this->assertEquals(
            $this->emptyArray,
            $this->calculateMethodClass->getMethods('GASD', "aSDASD", "ASDASD"));
        $this->assertEquals(
            $this->emptyArray,
            $this->calculateMethodClass->getMethods(123123123, "asdasd", "asdadasd"));
        $this->assertEquals(
            $this->emptyArray,
            $this->calculateMethodClass->getMethods(123123, 123123, "ASDASD"));
        $this->assertEquals(
            $this->emptyArray,
            $this->calculateMethodClass->getMethods(123123123, 123123123, 123123123));
        $this->assertEquals(
            $this->emptyArray,
            $this->calculateMethodClass->getMethods('GB', "aSD!!ASD", 0.100));
        $this->assertEquals(
            $this->emptyArray,
            $this->calculateMethodClass->getMethods('GB', 123123123123, 0.100));
    }

    /**
     * Test to ensure that only the expected empty array is returned from null
     * and incorrect data from the CalculateMethod class
     */
    public function testRoyalmailClassNull()
    {
        $this->assertEquals($this->emptyArray, $this->calculateMethodClass->getMethods(null, 123123123123, 0.100));
        $this->assertEquals($this->emptyArray, $this->calculateMethodClass->getMethods(null, null, 0.100));
        $this->assertEquals($this->emptyArray, $this->calculateMethodClass->getMethods('GB', null, 0.100));
        $this->assertEquals($this->emptyArray, $this->calculateMethodClass->getMethods('GB', null, null));
        $this->assertEquals($this->emptyArray, $this->calculateMethodClass->getMethods('GB', 123123123123, null));
        $this->assertEquals($this->emptyArray, $this->calculateMethodClass->getMethods(null, null, null));
    }

    /**
     * Test co compare the meta names vs the clean name to check that the correct value
     * exists and is being used.
     */
    public function testMethodToMetaVsCleanName()
    {
        foreach ($this->dataClass->mappingMethodToMeta as $array => $data) {
            $methodNotExist = false;
            foreach ($this->dataClass->mappingCleanNameToMethod as $method => $methodData) {
                // Check the the names are equal
                if ($data[self::METHOD_NAME_ROW_META_CSV] == $methodData[self::METHOD_NAME_ROW_CLEANNAME_CSV]) {
                    $methodNotExist = true;
                    $this->assertEquals($data[self::METHOD_CLEAN_NAME_ROW_META_CSV],
                        $methodData[self::METHOD_CLEAN_NAME_ROW_CLEANNAME_CSV],
                        sprintf("Clean names %s and %s were not equal", $data[self::METHOD_CLEAN_NAME_ROW_META_CSV],
                            $methodData[self::METHOD_CLEAN_NAME_ROW_CLEANNAME_CSV]));
                }

            }
            $this->assertTrue($methodNotExist,
                sprintf("%s was not found in CleanNameToMethod csv$.", $data[self::METHOD_NAME_ROW_META_CSV]));
        }
    }

    /**
     * Test for insurance value checking that the correct insurance value is being
     * used in the CSV files
     */
    public function testInsuranceValue()
    {
        foreach ($this->dataClass->mappingMethodToMeta as $array => $data) {
            foreach ($this->dataClass->mappingDeliveryToPrice as $method => $methodData) {
                if ($data[self::METHOD_NAME_ROW_META_CSV] == $methodData[self::METHOD_NAME_ROW_PRICE_CSV]) {
                    if ($methodData[self::INSURANCE_ROW_PRICE_CSV] != "") {
                        $this->assertEquals($data[self::INSURANCE_ROW_META_CSV],
                            $methodData[self::INSURANCE_ROW_PRICE_CSV]);
                        print_r($data, $method);
                    }
                }
            }
        }

        foreach ($this->dataClass->mappingMethodToMeta as $array => $data) {
            foreach ($this->dataClass->mappingCleanNameToMethod as $method => $methodData) {
                if ($data[self::METHOD_NAME_ROW_META_CSV] == $methodData[self::METHOD_NAME_ROW_CLEANNAME_CSV]) {
                    if ($methodData[self::INSURANCE_ROW_CLEANNAME_CSV] != "") {
                        $this->assertEquals($data[self::INSURANCE_ROW_META_CSV], $methodData[self::INSURANCE_ROW_CLEANNAME_CSV]);
                        print_r($data, $method);
                    }
                }
            }

        }
    }

    /**
     * Test to compare the method clean name vs the method group, ensuring that
     * the clean names are correct and exists.
     */
    public function testCleanNameVsMethodGroup()
    {
        foreach ($this->dataClass->mappingCleanNameToMethod as $array => $data) {
            foreach ($this->dataClass->mappingCleanNameMethodGroup as $method => $methodData) {
                if ($data[self::METHOD_CLEAN_NAME_ROW_CLEANNAME_CSV] == $methodData[self::METHOD_CLEAN_NAME_ROW_CLEANNAMEGROUP_CSV]) {
                    $this->assertEquals($data[self::METHOD_CLEAN_NAME_GROUP_CLEANNAME_CSV],
                        $methodData[self::METHOD_CLEAN_NAME_GROUP_CLEANNAMEGROUP_CSV]);
                }
            }
        }
    }
}