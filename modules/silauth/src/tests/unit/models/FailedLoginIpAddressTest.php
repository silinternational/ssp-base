<?php
namespace Sil\SilAuth\tests\unit\models;

use Sil\Psr3Adapters\Psr3EchoLogger;
use Sil\SilAuth\auth\Authenticator;
use Sil\SilAuth\models\FailedLoginIpAddress;
use Sil\SilAuth\time\UtcTime;
use PHPUnit\Framework\TestCase;

class FailedLoginIpAddressTest extends TestCase
{
    protected function setDbFixture($recordsData)
    {
        FailedLoginIpAddress::deleteAll();
        foreach ($recordsData as $recordData) {
            $model = new FailedLoginIpAddress($recordData);
            $this->assertTrue($model->insert(false));
        }
    }
    
    public function testCountRecentFailedLoginsFor()
    {
        // Arrange:
        $ipAddress = '100.110.120.130';
        $fixtures = [[
            'ip_address' => $ipAddress,
            'occurred_at_utc' => UtcTime::format('-61 minutes'), // Not recent.
        ], [
            'ip_address' => $ipAddress,
            'occurred_at_utc' => UtcTime::format('-59 minutes'), // Recent.
        ], [
            'ip_address' => $ipAddress,
            'occurred_at_utc' => UtcTime::format(), // Now (thus, recent).
        ]];
        $this->setDbFixture($fixtures);
        
        // Pre-assert:
        $this->assertCount(
            count($fixtures),
            FailedLoginIpAddress::getFailedLoginsFor($ipAddress)
        );

        // Act:
        $result = FailedLoginIpAddress::countRecentFailedLoginsFor($ipAddress);

        // Assert:
        $this->assertEquals(2, $result);
    }
    
    public function testGetMostRecentFailedLoginFor()
    {
        // Arrange:
        $ipAddress = '100.110.120.130';
        $nowDateTimeString = UtcTime::now();
        $fixtures = [[
            'ip_address' => $ipAddress,
            'occurred_at_utc' => UtcTime::format('-61 minutes'),
        ], [
            'ip_address' => $ipAddress,
            'occurred_at_utc' => $nowDateTimeString,
        ], [
            'ip_address' => $ipAddress,
            'occurred_at_utc' => UtcTime::format('-59 minutes'),
        ]];
        $this->setDbFixture($fixtures);
        
        // Act:
        $fliaRecord = FailedLoginIpAddress::getMostRecentFailedLoginFor($ipAddress);

        // Assert:
        $this->assertSame($nowDateTimeString, $fliaRecord->occurred_at_utc);
    }
    
    public function testIsCaptchaRequiredFor()
    {
        // Arrange:
        $captchaAfterNth = Authenticator::REQUIRE_CAPTCHA_AFTER_NTH_FAILED_LOGIN;
        $testCases = [[
            'dbFixture' => array_fill(
                0,
                $captchaAfterNth,
                ['ip_address' => '11.11.11.11', 'occurred_at_utc' => UtcTime::now()]
            ),
            'ipAddress' => '11.11.11.11',
            'expected' => true,
        ], [
            'dbFixture' => array_fill(
                0,
                $captchaAfterNth - 1,
                ['ip_address' => '22.22.22.22', 'occurred_at_utc' => UtcTime::now()]
            ),
            'ipAddress' => '22.22.22.22',
            'expected' => false,
        ]];
        foreach ($testCases as $testCase) {
            $this->setDbFixture($testCase['dbFixture']);

            // Act:
            $actual = FailedLoginIpAddress::isCaptchaRequiredFor($testCase['ipAddress']);

            // Assert:
            $this->assertSame($testCase['expected'], $actual);
        }
    }
    
    public function testIsRateLimitBlocking()
    {
        // Arrange:
        $blockAfterNth = Authenticator::BLOCK_AFTER_NTH_FAILED_LOGIN;
        $testCases = [[
            'dbFixture' => array_fill(
                0,
                $blockAfterNth,
                ['ip_address' => '11.11.11.11', 'occurred_at_utc' => UtcTime::now()]
            ),
            'ipAddress' => '11.11.11.11',
            'expected' => true,
        ], [
            'dbFixture' => array_fill(
                0,
                $blockAfterNth - 1,
                ['ip_address' => '22.22.22.22', 'occurred_at_utc' => UtcTime::now()]
            ),
            'ipAddress' => '22.22.22.22',
            'expected' => false,
        ]];
        foreach ($testCases as $testCase) {
            $this->setDbFixture($testCase['dbFixture']);

            // Act:
            $actual = FailedLoginIpAddress::isRateLimitBlocking($testCase['ipAddress']);

            // Assert:
            $this->assertSame($testCase['expected'], $actual);
        }
    }
    
    public function testRecordFailedLoginBy()
    {
        // Arrange:
        $ipAddress = '101.102.103.104';
        $dbFixture = [
            ['ip_address' => $ipAddress, 'occurred_at_utc' => UtcTime::format()]
        ];
        $this->setDbFixture($dbFixture);
        $logger = new Psr3EchoLogger();
        $expectedPre = count($dbFixture);
        $expectedPost = $expectedPre + 1;
        
        // Pre-assert:
        $this->assertCount(
            $expectedPre,
            FailedLoginIpAddress::getFailedLoginsFor($ipAddress)
        );
        
        // Act:
        FailedLoginIpAddress::recordFailedLoginBy([$ipAddress], $logger);
        
        // Assert:
        $this->assertCount(
            $expectedPost,
            FailedLoginIpAddress::getFailedLoginsFor($ipAddress)
        );
    }
    
    public function testResetFailedLoginsBy()
    {
        // Arrange:
        $ipAddress = '101.102.103.104';
        $otherIpAddress = '201.202.203.204';
        $logger = new Psr3EchoLogger();
        FailedLoginIpAddress::deleteAll();
        FailedLoginIpAddress::recordFailedLoginBy(
            [$ipAddress, $otherIpAddress],
            $logger
        );
        
        // Pre-assert:
        $this->assertCount(1, FailedLoginIpAddress::getFailedLoginsFor($ipAddress));
        $this->assertCount(1, FailedLoginIpAddress::getFailedLoginsFor($otherIpAddress));
        
        // Act:
        FailedLoginIpAddress::resetFailedLoginsBy([$ipAddress]);
        
        // Assert:
        $this->assertCount(0, FailedLoginIpAddress::getFailedLoginsFor($ipAddress));
        $this->assertCount(1, FailedLoginIpAddress::getFailedLoginsFor($otherIpAddress));
    }
}
