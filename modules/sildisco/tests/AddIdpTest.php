<?php


class AddIdpTest extends PHPUnit_Framework_TestCase
{

    private static function getNameID($idp) {
        return [
            'saml:sp:IdP' => $idp,
            'saml:sp:NameID' => [
                [
                    'Format' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
                    'Value' => 'Tester1_Smith',
                    'SPNameQualifier' => 'http://ssp-sp1.local',
                ],
            ],
            'Attributes' => [],
            'metadataPath' => __DIR__ . '/fixtures/metadata/',
        ];
    }

    /**
     * Helper function to run the filter with a given configuration.
     *
     * @param array $config  The filter configuration.
     * @param array $request  The request state.
     * @return array  The state array after processing.
     */
    private static function processAddIdp2NameId(array $config, array $request)
    {
        $filter = new \SimpleSAML\Module\sildisco\Auth\Process\AddIdp2NameId($config, NULL);
        $filter->process($request);
        return $request;
    }

    /*
     * Test with IdP metadata not having an IDPNamespace entry
     * @expectedException \SimpleSAML\Error\Exception
     */
    public function testAddIdp2NameId_NoIDPNamespace()
    {
        $this->setExpectedException('\SimpleSAML\Error\Exception');
        $config = [ 'test' => ['value1', 'value2'], ];
        $request = self::getNameID('idp-bare');

        self::processAddIdp2NameId($config, $request);
    }


    /*
     * Test with IdP metadata not having an IDPNamespace entry
     * @expectedException \SimpleSAML\Error\Exception
     */
    public function testAddIdp2NameId_EmptyIDPNamespace()
    {
        $this->setExpectedException('\SimpleSAML\Error\Exception');
        $config = [ 'test' => ['value1', 'value2'], ];
        $request = self::getNameID('idp-empty');
        self::processAddIdp2NameId($config, $request);
    }

    /*
     * Test with IdP metadata not having an IDPNamespace entry
     * @expectedException \SimpleSAML\Error\Exception
     */
    public function testAddIdp2NameId_BadIDPNamespace()
    {
        $this->setExpectedException('\SimpleSAML\Error\Exception');
        $config = [
            'test' => ['value1', 'value2'],
        ];
        $request = self::getNameID('idp-bad');
        self::processAddIdp2NameId($config, $request);
    }



    /*
     * Test with IdP metadata having a good IDPNamespace entry
     */
    public function testAddIdp2NameId_GoodString()
    {
        $config = ['test' => ['value1', 'value2']];
        $state = [
            'saml:sp:IdP' => 'idp-good',
            'saml:sp:NameID' => 'Tester1_SmithA',
            'Attributes' => [],
            'metadataPath' => __DIR__ . '/fixtures/metadata/',
        ];

        $newNameID = $state['saml:sp:NameID'];
        $newNameID = 'Tester1_SmithA@idpGood';

        $expected = $state;
        $expected['saml:NameID']['urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified'] = $newNameID;

        $results = self::processAddIdp2NameId($config, $state);
        $this->assertEquals($expected, $results);
    }
    /*
     * Test with IdP metadata having a good IDPNamespace entry
     */
    public function testAddIdp2NameId_GoodArray()
    {
        $config = ['test' => ['value1', 'value2']];
        $state = [
            'saml:sp:IdP' => 'idp-good',
            'saml:sp:NameID' => [
                'Format' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:transient',
                'Value' => 'Tester1_SmithA',
                'SPNameQualifier' => 'http://ssp-sp1.local',
            ],
            'Attributes' => [],
            'metadataPath' => __DIR__ . '/fixtures/metadata/',
        ];

        $newNameID = $state['saml:sp:NameID'];
        $newNameID['Value'] = 'Tester1_SmithA@idpGood';

        $expected = $state;
        $expected['saml:NameID']['urn:oasis:names:tc:SAML:1.1:nameid-format:transient'] = $newNameID;

        $results = self::processAddIdp2NameId($config, $state);

        $this->assertEquals($expected, $results);
    }

}
