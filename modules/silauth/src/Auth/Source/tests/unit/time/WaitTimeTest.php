<?php
namespace SimpleSAML\Module\silauth\Auth\Source\tests\unit\time;

use SimpleSAML\Module\silauth\Auth\Source\time\WaitTime;
use PHPUnit\Framework\TestCase;

class WaitTimeTest extends TestCase
{
    public function testGetFriendlyWaitTimeFor()
    {
        // Arrange:
        $testCases = [
            ['secondsToWait' => 0, 'expected' => '5 seconds'],
            ['secondsToWait' => 1, 'expected' => '5 seconds'],
            ['secondsToWait' => 5, 'expected' => '5 seconds'],
            ['secondsToWait' => 6, 'expected' => '10 seconds'],
            ['secondsToWait' => 17, 'expected' => '20 seconds'],
            ['secondsToWait' => 22, 'expected' => '30 seconds'],
            ['secondsToWait' => 41, 'expected' => '1 minute'],
            ['secondsToWait' => 90, 'expected' => '2 minutes'],
            ['secondsToWait' => 119, 'expected' => '2 minutes'],
            ['secondsToWait' => 120, 'expected' => '2 minutes'],
            ['secondsToWait' => 121, 'expected' => '3 minutes'],
        ];
        foreach ($testCases as $testCase) {
            $waitTime = new WaitTime($testCase['secondsToWait']);
            
            // Act:
            $actual = (string)$waitTime;
            
            // Assert:
            $this->assertSame($testCase['expected'], $actual, sprintf(
                'Expected %s second(s) to result in %s, not %s.',
                var_export($testCase['secondsToWait'], true),
                var_export($testCase['expected'], true),
                var_export($actual, true)
            ));
        }
    }
    
    public function testGetLongestWaitTime()
    {
        // Arrange:
        $testCases = [
            ['durations' => [], 'expectException' => '\InvalidArgumentException'],
            ['durations' => [0, 0], 'expected' => new WaitTime(0)],
            ['durations' => [0, 1], 'expected' => new WaitTime(1)],
            ['durations' => [1, 0], 'expected' => new WaitTime(1)],
            ['durations' => [6], 'expected' => new WaitTime(6)],
            ['durations' => [5, 5, 6], 'expected' => new WaitTime(6)],
            ['durations' => [5, 6, 5], 'expected' => new WaitTime(6)],
            ['durations' => [6, 5, 5], 'expected' => new WaitTime(6)],
            ['durations' => [0, 17], 'expected' => new WaitTime(17)],
            ['durations' => [17, 5], 'expected' => new WaitTime(17)],
        ];
        foreach ($testCases as $testCase) {
            if (array_key_exists('expectException', $testCase)) {
                $this->expectException($testCase['expectException']);
            }
            
            // Act:
            $actual = WaitTime::getLongestWaitTime($testCase['durations']);
            
            // Assert:
            $this->assertEquals($testCase['expected'], $actual, sprintf(
                'Expected the longest of %s second(s) to be a wait time of %s, not %s.',
                json_encode($testCase['durations']),
                $testCase['expected'],
                $actual
            ));
        }
    }
}
