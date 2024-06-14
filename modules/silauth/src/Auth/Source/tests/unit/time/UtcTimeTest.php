<?php
namespace SimpleSAML\Module\silauth\Auth\Source\tests\unit\time;

use SimpleSAML\Module\silauth\Auth\Source\time\UtcTime;
use PHPUnit\Framework\TestCase;

class UtcTimeTest extends TestCase
{
    public function testFormat()
    {
        // Arrange:
        $testCases = [
            [
                'dateTimeString' => '1 Jan 2000 00:00:00 -0000',
                'expected' => '2000-01-01 00:00:00',
            ], [
                'dateTimeString' => '2016-Dec-25 12:00pm',
                'expected' => '2016-12-25 12:00:00',
            ],
        ];
        foreach ($testCases as $testCase) {
            
            // Act:
            $actual = UtcTime::format($testCase['dateTimeString']);
            
            // Assert:
            $this->assertSame($testCase['expected'], $actual);
        }
    }
    
    public function testGetRemainingSeconds()
    {
        // Arrange:
        $testCases = [
            ['total' => 1, 'elapsed' => null, 'expectException' => '\TypeError'],
            ['total' => null, 'elapsed' => 1, 'expectException' => '\TypeError'],
            ['total' => 1, 'elapsed' => '1', 'expectException' => '\TypeError'],
            ['total' => '1', 'elapsed' => 1, 'expectException' => '\TypeError'],
            ['total' => -1, 'elapsed' => 1, 'expected' => 0],
            ['total' => -1, 'elapsed' => 0, 'expected' => 0],
            ['total' => 0, 'elapsed' => 0, 'expected' => 0],
            ['total' => 0, 'elapsed' => 5, 'expected' => 0],
            ['total' => 5, 'elapsed' => 0, 'expected' => 5],
            ['total' => 5, 'elapsed' => 5, 'expected' => 0],
            ['total' => 8, 'elapsed' => 5, 'expected' => 3],
            ['total' => 60, 'elapsed' => 45, 'expected' => 15],
        ];
        foreach ($testCases as $testCase) {
            $total = $testCase['total'];
            $elapsed = $testCase['elapsed'];
            $expected = $testCase['expected'] ?? null;
            $expectException = $testCase['expectException'] ?? null;
            
            // Pre-assert:
            if ($expectException !== null) {
                $this->expectException($expectException);
            }
            
            // Act:
            $actual = UtcTime::getRemainingSeconds($total, $elapsed);
            
            // Assert:
            if ($expectException !== null) {
                $this->fail(sprintf(
                    'Expected a %s to be thrown for (total: %s, elapsed: %s).',
                    $expectException,
                    var_export($total, true),
                    var_export($elapsed, true)
                ));
            }
            $this->assertSame($expected, $actual, sprintf(
                'Expected (total: %s, elapsed: %s) to result in %s, not %s.',
                var_export($total, true),
                var_export($elapsed, true),
                var_export($expected, true),
                var_export($actual, true)
            ));
        }
    }
    
    public function testGetSecondsSinceDateTime()
    {
        // Arrange:
        $testCases = [
            ['value' => '1970-01-01 00:00:00', 'expected' => time()],
            ['value' => UtcTime::format(), 'expected' => 0],
            ['value' => UtcTime::format('-10 seconds'), 'expected' => 10],
            ['value' => UtcTime::format('-2 hours'), 'expected' => 7200],
        ];
        foreach ($testCases as $testCase) {
            
            // Act:
            $actual = UtcTime::getSecondsSinceDateTime($testCase['value']);
            
            // Assert:
            $this->assertEqualsWithDelta(
                $testCase['expected'],
                $actual,
                1,
                sprintf('Expected %s to result in %s, not %s.',
                    var_export($testCase['value'], true),
                    var_export($testCase['expected'], true),
                    var_export($actual, true)
                )
            );
        }
    }
    
    public function testGetSecondsSinceDateTimeEmptyString()
    {
        $this->expectException('\InvalidArgumentException');
        UtcTime::getSecondsSinceDateTime('');
    }
    
    public function testGetSecondsSinceDateTimeInvalidDateTimeString()
    {
        $this->expectException('\Exception');
        UtcTime::getSecondsSinceDateTime('asdf');
    }
    
    public function testGetSecondsSinceDateTimeNull()
    {
        $this->expectException('\TypeError');
        UtcTime::getSecondsSinceDateTime(null);
    }
    
    public function testGetSecondsUntil()
    {
        // Arrange:
        $dayOneString = 'Tue, 13 Dec 2016 00:00:00 -0500';
        $dayTwoString = 'Wed, 14 Dec 2016 00:00:00 -0500';
        $expected = 86400; // 86400 = seconds in a day
        $dayOneUtcTime = new UtcTime($dayOneString);
        $dayTwoUtcTime = new UtcTime($dayTwoString);
        
        // Act:
        $actual = $dayOneUtcTime->getSecondsUntil($dayTwoUtcTime);
        
        // Assert:
        $this->assertSame($expected, $actual);
    }
    
    public function testGetTimestamp()
    {
        // Arrange:
        $timestamp = time();
        $utcTime = new UtcTime(date('r', $timestamp));
        
        // Act:
        $result = $utcTime->getTimestamp();
        
        // Assert:
        $this->assertSame($timestamp, $result);
    }
    
    public function testNow()
    {
        // Arrange:
        $expected = gmdate(UtcTime::DATE_TIME_FORMAT, time());
        
        // Act:
        $actual = UtcTime::now();
        
        // Assert:
        $this->assertSame($expected, $actual);
    }
}
