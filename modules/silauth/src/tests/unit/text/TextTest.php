<?php
namespace Sil\SilAuth\tests\unit\text;

use Sil\SilAuth\text\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    public function testSanitizeString()
    {
        // Arrange:
        $testCases = [
            ['input' => '', 'expected' => ''],
            ['input' => null, 'expected' => ''],
            ['input' => false, 'expected' => ''],
            ['input' => true, 'expected' => ''],
            ['input' => 'null', 'expected' => 'null'],
            ['input' => 'false', 'expected' => 'false'],
            ['input' => 'true', 'expected' => 'true'],
            ['input' => 'abc XYZ', 'expected' => 'abc XYZ'],
            ['input' => ' leading space', 'expected' => 'leading space'],
            ['input' => 'trailing space ', 'expected' => 'trailing space'],
            ['input' => 'trailing space ', 'expected' => 'trailing space'],
            ['input' => 'low ASCII char: ' . chr(2), 'expected' => 'low ASCII char:'],
            ['input' => 'high ASCII char: ' . chr(160), 'expected' => 'high ASCII char: ' . chr(160)],
            ['input' => 'with `backticks`', 'expected' => 'with backticks'],
        ];
        foreach ($testCases as $testCase) {
            
            // Act:
            $actual = Text::sanitizeString($testCase['input']);
            
            // Assert:
            $this->assertSame($testCase['expected'], $actual, sprintf(
                'Expected sanitizing %s to result in %s, not %s.',
                var_export($testCase['input'], true),
                var_export($testCase['expected'], true),
                var_export($actual, true)
            ));
        }
    }
}
