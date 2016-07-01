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
 * Class CarrierTest
 * Test class to run phpunit tests on the library.
 *
 * @category Meanbee
 * @package  Meanbee\Royalmail
 * @author   Meanbee Limited <hello@meanbee.com>
 * @license  OSL v. 3.0
 * @link     http://github.com/meanbee/royalmail-php-library
 */
class CarrierTest extends \PHPUnit_Framework_TestCase
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

    /**
     * Variable for carrier class
     *
     * @var Carrier $_carrier
     */
    private $_carrier;

    /**
     * Variable for data class
     *
     * @var Data $_dataClass
     */
    private $_dataClass;

    private $_emptyArray;
    private $_testDataClassArray;

    /**
     * Setup the necessary classes and data
     *
     * @return null
     */
    public function setUp()
    {
        /**
         * Set the carrier class
         *
         * @var Carrier
         */
        $this->_carrier = new Carrier();
        $this->_dataClass = new Data(
            $this->_carrier->getCsvCountryCode(),
            $this->_carrier->getCsvZoneToDeliveryMethod(),
            $this->_carrier->getCsvDeliveryMethodMeta(),
            $this->_carrier->getCsvDeliveryToPrice(),
            $this->_carrier->getCsvCleanNameMethodGroup()
        );

        $this->_emptyArray = [];
        $this->_testDataClassArray = array(
            'shippingMethodName'      => 'test',
            'minimumWeight'           => 1.00,
            'maximumWeight'           => 5.00,
            'methodPrice'             => 0.99,
            'insuranceValue'          => 10,
            'shippingMethodNameClean' => 'Test',
            'size'                    => 'Small',
        );
    }

    /**
     * Cleans up the used classes
     *
     * @return null
     */
    public function tearDown()
    {
        $this->_carrier = null;
        $this->_dataClass = null;
    }

    /**
     * Test to ensure that the calculate method class is returning
     *
     * @return null
     */
    public function testRoyalmailClassRealValues()
    {
        $this->assertNotEmpty($this->_carrier->getRates('GB', 20, 0.050));
    }

    /**
     * Test to ensure that the calculate method class is returning rates with
     * the ignore insurance flag set to true. 37 methods are expected to be
     * returned.
     *
     * @return null
     */
    public function testRoyalmailClassRealValuesAll()
    {
        $this->assertCount(
            37,
            $this->_carrier->getRates('GB', 20, 0.050, true),
            "Array size from getRates did not match on
            the expected size of 37 methods returned."
        );
    }

    /**
     * Test to ensure that the calculate method class is returning rates with
     * the ignore insurance flag set to false. 30 methods are expected to be
     * returned.
     *
     * @return null
     */
    public function testRoyalmailClassRealValuesAllFalse()
    {
        $this->assertCount(
            30,
            $this->_carrier->getRates('GB', 20, 0.050, false),
            "Array size from getRates did not match on
             the expected size of 37 methods returned."
        );
    }

    /**
     * Test to compare the returned data from the Data class to expected values
     *
     * @return null
     */
    public function testRoyalmailMethodRealValues()
    {
        $calculatedMethods = $this->_dataClass->calculateMethods('GB', 19.99, 0.050);
        foreach ($calculatedMethods as $calculatedMethod => $arrayContents) {
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['shippingMethodName']
                ),
                gettype($arrayContents['shippingMethodName']),
                "shippingMethodName array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['minimumWeight']
                ),
                gettype($arrayContents['minimumWeight']),
                "minimumWeight array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['maximumWeight']
                ),
                gettype($arrayContents['maximumWeight']),
                "maximumWeight array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['methodPrice']
                ),
                gettype($arrayContents['methodPrice']),
                "methodPrice array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['insuranceValue']
                ),
                gettype($arrayContents['insuranceValue']),
                "insuranceValue array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['shippingMethodNameClean']
                ),
                gettype($arrayContents['shippingMethodNameClean']),
                "shippingMethodNameClean array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['size']
                ),
                gettype($arrayContents['size']),
                "size array value not equal to correct type."
            );
        }
    }

    /**
     * Test to ensure the only the expected empty array
     * is returned from incorrect data to the data class
     *
     * @return null
     */
    public function testRoyalmailMethodFake()
    {
        $this->assertEquals(
            $this->_emptyArray,
            $this->_dataClass->calculateMethods(
                'GASD', "aSDASD", "ASDASD"
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_dataClass->calculateMethods(
                123123123, "asdasd", "asdadasd"
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_dataClass->calculateMethods(
                123123, 123123, "ASDASD"
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_dataClass->calculateMethods(
                123123123, 123123123, 123123123
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_dataClass->calculateMethods(
                'GB', "aSD!!ASD", 0.100
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_dataClass->calculateMethods(
                'GB', 123123123123, 0.100
            )
        );
    }

    /**
     * Test to ensure that only the expected empty array
     * is returned from null and incorrect data
     * from the Data class
     *
     * @return null
     */
    public function testRoyalmailMethodNull()
    {
        $this->assertEquals(
            $this->_emptyArray,
            $this->_dataClass->calculateMethods(
                null, 123123123123, 0.100
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_dataClass->calculateMethods(
                null, null, 0.100
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_dataClass->calculateMethods(
                'GB', null, 0.100
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_dataClass->calculateMethods(
                'GB', null, null
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_dataClass->calculateMethods(
                'GB', 123123123123, null
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_dataClass->calculateMethods(
                null, null, null
            )
        );
    }

    /**
     * Test to ensure that only the expected empty array is returned from incorrect
     * data from the CalculateMethod class
     *
     * @return null
     */
    public function testRoyalmailClassFake()
    {
        $this->assertEquals(
            $this->_emptyArray,
            $this->_carrier->getRates(
                'GASD', "aSDASD", "ASDASD"
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_carrier->getRates(
                123123123, "asdasd", "asdadasd"
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_carrier->getRates(
                123123, 123123, "ASDASD"
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_carrier->getRates(
                123123123, 123123123, 123123123
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_carrier->getRates(
                'GB', "aSD!!ASD", 0.100
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_carrier->getRates(
                'GB', 123123123123, 0.100
            )
        );
    }

    /**
     * Test to ensure that only the expected empty array is returned from null
     * and incorrect data from the CalculateMethod class
     *
     * @return null
     */
    public function testRoyalmailClassNull()
    {
        $this->assertEquals(
            $this->_emptyArray,
            $this->_carrier->getRates(
                null, 123123123123, 0.100
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_carrier->getRates(
                null, null, 0.100
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_carrier->getRates(
                'GB', null, 0.100
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_carrier->getRates(
                'GB', null, null
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_carrier->getRates(
                'GB', 123123123123, null
            )
        );
        $this->assertEquals(
            $this->_emptyArray,
            $this->_carrier->getRates(
                null, null, null
            )
        );
    }

    /**
     * Test for insurance value checking that the correct insurance value is being
     * used in the CSV files
     *
     * @return null
     */
    public function testInsuranceValue()
    {
        foreach ($this->_dataClass->getMappingMethodToMeta() as $array => $data) {
            foreach ($this->_dataClass->getMappingDeliveryToPrice()
                as $method => $methodData) {
                if ($data[self::METHOD_NAME_ROW_META_CSV] == $methodData[self::METHOD_NAME_ROW_PRICE_CSV]
                ) {
                    if ($methodData[self::INSURANCE_ROW_PRICE_CSV] != "") {
                        $this->assertEquals(
                            $data[self::INSURANCE_ROW_META_CSV],
                            $methodData[self::INSURANCE_ROW_PRICE_CSV],
                            sprintf(
                                "Insurance values %s from mappingMethodToMeta and
                                %s from mappingDeliveryToPrice were not equal.",
                                $data[self::INSURANCE_ROW_META_CSV],
                                $methodData[self::INSURANCE_ROW_PRICE_CSV]
                            )
                        );
                    }
                }
            }
        }
    }

    /**
     * Test get a full list of royal mail methods
     *
     * @return null
     */
    public function testGetAllMethods()
    {
        /**
         * All methods from the carrier class
         *
         * @var array $methods
         */
        $methods = $this->_carrier->getAllMethods();

        $this->assertInternalType('array', $methods);
        $this->assertTrue(count($methods) > 0);

        foreach ($methods as $code => $name) {
            $this->assertInternalType('string', $code);
            $this->assertFalse(strpos($code, ' '));

            $this->assertInternalType('string', $name);
            $this->assertTrue(strpos($name, ' ') !== false);
        }
    }
}
