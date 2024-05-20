<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../vendor/simplesamlphp/simplesamlphp/modules/sildisco/lib/IdPDisco.php';

use PHPUnit\Framework\TestCase;
use SimpleSAML\Module\sildisco\IdPDisco;

class IdpDiscoTest extends TestCase
{
    
    public function testEnableBetaEnabledEmpty()
    {
        $idpList = [];
        $results = IdPDisco::enableBetaEnabled($idpList);
        $expected = [];
        $this->assertEquals($expected, $results);
    }

    public function testEnableBetaEnabledNoChange()
    {
        $isBetaEnabled = 1;
        $enabledKey = IdPDisco::$enabledMdKey;
        $idpList = [
            'idp1' => [$enabledKey => false],
            'idp2' => [$enabledKey => true],
        ];
        $expected = $idpList;

        $results = IdPDisco::enableBetaEnabled($idpList, $isBetaEnabled);
        $this->assertEquals($expected, $results);
    }

    public function testEnableBetaEnabledChange()
    {
        $isBetaEnabled = 1;
        $enabledKey = IdPDisco::$enabledMdKey;
        $betaEnabledKey = IdPDisco::$betaEnabledMdKey;
        $idpList = [
            'idp1' => [$enabledKey => false],
            'idp2' => [$enabledKey => true, $betaEnabledKey => true],
            'idp3' => [$enabledKey => false, $betaEnabledKey => true],
            'idp4' => [$enabledKey => false, $betaEnabledKey => false],
        ];
        $expected = $idpList;
        $expected['idp3'][$enabledKey] = true;

        $results = IdPDisco::enableBetaEnabled($idpList, $isBetaEnabled);
        $this->assertEquals($expected, $results);
    }

}
