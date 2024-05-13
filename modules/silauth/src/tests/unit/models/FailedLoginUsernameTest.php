<?php
namespace Sil\SilAuth\tests\unit\models;

use Sil\Psr3Adapters\Psr3EchoLogger;
use Sil\SilAuth\auth\Authenticator;
use Sil\SilAuth\models\FailedLoginUsername;
use Sil\SilAuth\time\UtcTime;
use PHPUnit\Framework\TestCase;

class FailedLoginUsernameTest extends TestCase
{
    protected function setDbFixture($recordsData)
    {
        FailedLoginUsername::deleteAll();
        foreach ($recordsData as $recordData) {
            $model = new FailedLoginUsername($recordData);
            $this->assertTrue($model->insert(false));
        }
    }
    
    public function testCountRecentFailedLoginsFor()
    {
        // Arrange:
        $username = 'john_smith';
        $fixtures = [[
            'username' => $username,
            'occurred_at_utc' => UtcTime::format('-61 minutes'), // Not recent.
        ], [
            'username' => $username,
            'occurred_at_utc' => UtcTime::format('-59 minutes'), // Recent.
        ], [
            'username' => $username,
            'occurred_at_utc' => UtcTime::format(), // Now (thus, recent).
        ]];
        $this->setDbFixture($fixtures);
        
        // Pre-assert:
        $this->assertCount(
            count($fixtures),
            FailedLoginUsername::getFailedLoginsFor($username)
        );

        // Act:
        $result = FailedLoginUsername::countRecentFailedLoginsFor($username);

        // Assert:
        $this->assertEquals(2, $result);
    }
    
    public function testGetMostRecentFailedLoginFor()
    {
        // Arrange:
        $username = 'dummy_username';
        $nowDateTimeString = UtcTime::now();
        $fixtures = [[
            'username' => $username,
            'occurred_at_utc' => UtcTime::format('-61 minutes'),
        ], [
            'username' => $username,
            'occurred_at_utc' => $nowDateTimeString,
        ], [
            'username' => $username,
            'occurred_at_utc' => UtcTime::format('-59 minutes'),
        ]];
        $this->setDbFixture($fixtures);
        
        // Act:
        $fliaRecord = FailedLoginUsername::getMostRecentFailedLoginFor($username);

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
                ['username' => 'dummy_username', 'occurred_at_utc' => UtcTime::now()]
            ),
            'username' => 'dummy_username',
            'expected' => true,
        ], [
            'dbFixture' => array_fill(
                0,
                $captchaAfterNth - 1,
                ['username' => 'dummy_other_username', 'occurred_at_utc' => UtcTime::now()]
            ),
            'username' => 'dummy_other_username',
            'expected' => false,
        ]];
        foreach ($testCases as $testCase) {
            $this->setDbFixture($testCase['dbFixture']);

            // Act:
            $actual = FailedLoginUsername::isCaptchaRequiredFor($testCase['username']);

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
                ['username' => 'dummy_username', 'occurred_at_utc' => UtcTime::now()]
            ),
            'username' => 'dummy_username',
            'expected' => true,
        ], [
            'dbFixture' => array_fill(
                0,
                $blockAfterNth - 1,
                ['username' => 'dummy_other_username', 'occurred_at_utc' => UtcTime::now()]
            ),
            'username' => 'dummy_other_username',
            'expected' => false,
        ]];
        foreach ($testCases as $testCase) {
            $this->setDbFixture($testCase['dbFixture']);

            // Act:
            $actual = FailedLoginUsername::isRateLimitBlocking($testCase['username']);

            // Assert:
            $this->assertSame($testCase['expected'], $actual);
        }
    }
    
    public function testRecordFailedLoginBy()
    {
        // Arrange:
        $username = 'dummy_username';
        $dbFixture = [
            ['username' => $username, 'occurred_at_utc' => UtcTime::format()]
        ];
        $this->setDbFixture($dbFixture);
        $logger = new Psr3EchoLogger();
        $expectedPre = count($dbFixture);
        $expectedPost = $expectedPre + 1;
        
        // Pre-assert:
        $this->assertCount(
            $expectedPre,
            FailedLoginUsername::getFailedLoginsFor($username)
        );
        
        // Act:
        FailedLoginUsername::recordFailedLoginBy($username, $logger);
        
        // Assert:
        $this->assertCount(
            $expectedPost,
            FailedLoginUsername::getFailedLoginsFor($username)
        );
    }
    
    public function testResetFailedLoginsBy()
    {
        // Arrange:
        $username = 'dummy_username';
        $otherUsername = 'dummy_other_username';
        $dbFixture = [
            ['username' => $username, 'occurred_at_utc' => UtcTime::format()],
            ['username' => $otherUsername, 'occurred_at_utc' => UtcTime::format()],
        ];
        $this->setDbFixture($dbFixture);
        
        // Pre-assert:
        $this->assertCount(1, FailedLoginUsername::getFailedLoginsFor($username));
        $this->assertCount(1, FailedLoginUsername::getFailedLoginsFor($otherUsername));
        
        // Act:
        FailedLoginUsername::resetFailedLoginsBy($username);
        
        // Assert:
        $this->assertCount(0, FailedLoginUsername::getFailedLoginsFor($username));
        $this->assertCount(1, FailedLoginUsername::getFailedLoginsFor($otherUsername));
    }
}
