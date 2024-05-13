<?php
namespace Sil\SilAuth\tests\unit\config;

use Sil\SilAuth\config\ConfigManager;
use PHPUnit\Framework\TestCase;

class ConfigManagerTest extends TestCase
{
    public function testGetSspConfig()
    {
        // Arrange: (n/a)
        
        // Act:
        $sspConfig = ConfigManager::getSspConfig();
        
        // Assert:
        $this->assertTrue(is_array($sspConfig), sprintf(
            'Expected an array, got this: %s',
            var_export($sspConfig, true)
        ));
    }
    
    public function testGetSspConfigFor()
    {
        // Arrange:
        $category = 'mysql';
        
        // Act:
        $result = ConfigManager::getSspConfigFor($category);
        
        // Assert:
        $this->assertArrayHasKey('database', $result, var_export($result, true));
    }
    
    public function testRemoveCategory()
    {
        // Arrange:
        $testCases = [
            ['key' => null, 'expected' => null],
            ['key' => '', 'expected' => ''],
            ['key' => '.', 'expected' => ''],
            ['key' => '.abc', 'expected' => 'abc'],
            ['key' => 'category.subKey', 'expected' => 'subKey'],
            ['key' => 'category.subCategory.subKey', 'expected' => 'subCategory.subKey'],
        ];
        foreach ($testCases as $testCase) {
            
            // Act:
            $actual = ConfigManager::removeCategory($testCase['key']);
            
            // Assert:
            $this->assertSame($testCase['expected'], $actual, sprintf(
                'Expected removing the category from %s result in %s, not %s.',
                var_export($testCase['key'], true),
                var_export($testCase['expected'], true),
                var_export($actual, true)
            ));
        }
    }
}
