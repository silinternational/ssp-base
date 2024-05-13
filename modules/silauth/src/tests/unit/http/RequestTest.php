<?php
namespace Sil\SilAuth\tests\unit\http;

use PHPUnit\Framework\TestCase;
use Sil\SilAuth\http\Request;

class RequestTest extends TestCase
{
    public function testIsTrustedIpAddress()
    {
        // Arrange:
        $trustedIpAddresses = [
            '11.11.11.11',
            '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
        ];
        $testCases = [[
            'ipAddress' => '11.11.11.11',
            'trustedIpAddresses' => $trustedIpAddresses,
            'expected' => true,
        ], [
            'ipAddress' => '22.22.22.22',
            'trustedIpAddresses' => $trustedIpAddresses,
            'expected' => false,
        ], [
            'ipAddress' => '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
            'trustedIpAddresses' => $trustedIpAddresses,
            'expected' => true,
        ], [
            'ipAddress' => '2001:0DB8:85A3:0000:0000:8A2E:0370:7334',
            'trustedIpAddresses' => $trustedIpAddresses,
            'expected' => true,
        ], [
            'ipAddress' => '2001:0db8:85a3:0000:0000:8a2e:0370:9999',
            'trustedIpAddresses' => $trustedIpAddresses,
            'expected' => false,
        ]];
        foreach ($testCases as $testCase) {
            $request = new Request($testCase['trustedIpAddresses']);

            // Act:
            $actual = $request->isTrustedIpAddress($testCase['ipAddress']);
            
            // Assert:
            $this->assertSame($testCase['expected'], $actual, sprintf(
                'Expected %s %sto be trusted.',
                var_export($testCase['ipAddress'], true),
                ($testCase['expected'] ? '' : 'not ')
            ));
        }
    }
}
