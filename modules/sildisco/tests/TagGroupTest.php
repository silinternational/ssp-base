<?php

use PHPUnit\Framework\TestCase;
use SimpleSAML\Configuration;
use SimpleSAML\Module\sildisco\Auth\Process\TagGroup;

class TagGroupTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        Configuration::setConfigDir(__DIR__ . '/fixtures/config/');
    }

    /**
     * Helper function to run the filter with a given configuration.
     *
     * @param array $config The filter configuration.
     * @param array $request The request state.
     * @return array  The state array after processing.
     */
    private static function processTagGroup(array $config, array $request)
    {
        $filter = new TagGroup($config, NULL);
        $filter->process($request);
        return $request;
    }

    /*
     * Test with oid and friendly keys for groups
     * @expectedException \SimpleSAML\Error\Exception
     */
    public function testTagGroup_Both()
    {
        $config = ['test' => ['value1', 'value2'],];
        $request = [
            "saml:sp:IdP" => 'idp-bare',
            "Attributes" => [
                'urn:oid:2.5.4.31' => ['ADMINS'],
                'member' => ['ADMINS'],
            ],
        ];

        $expected = $request;
        $expected["Attributes"]['urn:oid:2.5.4.31'] = ['idp|idp-bare|ADMINS'];
        $expected["Attributes"]['member'] = ['idp|idp-bare|ADMINS'];
        $results = self::processTagGroup($config, $request);
        $this->assertEquals($expected, $results);
    }


    /*
     * Test with friendly key for groups
     * @expectedException \SimpleSAML\Error\Exception
     */
    public function testTagGroup_Member()
    {
        $config = ['test' => ['value1', 'value2'],];
        $request = [
            "saml:sp:IdP" => 'idp-bare',
            "Attributes" => [
                'member' => ['ADMINS'],
            ],
        ];

        $expected = $request;
        $expected["Attributes"]['member'] = ['idp|idp-bare|ADMINS'];
        $results = self::processTagGroup($config, $request);
        $this->assertEquals($expected, $results);
    }

    /*
     * Test with oid key for groups
     * @expectedException \SimpleSAML\Error\Exception
     */
    public function testTagGroup_Oid()
    {
        $config = ['test' => ['value1', 'value2'],];
        $request = [
            "saml:sp:IdP" => 'idp-bare',
            "Attributes" => [
                'urn:oid:2.5.4.31' => ['ADMINS'],
            ],
        ];

        $expected = $request;
        $expected["Attributes"]['urn:oid:2.5.4.31'] = ['idp|idp-bare|ADMINS'];
        $results = self::processTagGroup($config, $request);
        $this->assertEquals($expected, $results);
    }

    /*
     * Test with oid key for groups
     * @expectedException \SimpleSAML\Error\Exception
     */
    public function testTagGroup_IdpGood()
    {
        $config = ['test' => ['value1', 'value2'],];
        $request = [
            "saml:sp:IdP" => 'idp-good',
            "Attributes" => [
                'urn:oid:2.5.4.31' => ['ADMINS'],
            ],
        ];

        $expected = $request;
        $expected["Attributes"]['urn:oid:2.5.4.31'] = ['idp|idpGood|ADMINS'];
        $results = self::processTagGroup($config, $request);
        $this->assertEquals($expected, $results);
    }
}
