<?php
namespace Sil\IdPHubTests;

include __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Sil\SspUtils\Metadata;
use Sil\SspUtils\DiscoUtils;
use Sil\SspUtils\Utils;

class MetadataTest extends TestCase
{
    // SP Metadata entry to request that certain tests be skipped
    const SkipTestsKey = "SkipTests";    
    
    public $metadataPath = __DIR__ . '/../vendor/simplesamlphp/simplesamlphp/metadata';

    public function testLintTestMetadataFiles()
    {
        $spFiles = $this->getSpMetadataFiles();
        foreach($spFiles as $file) {
            $output = $returnVal = null;
            exec('php -l ' . $file, $output, $returnVal);
            $this->assertEquals(
                0,
                $returnVal,
                'Lint test failed for file: ' . $file . '. Error: ' . print_r($output, true)
            );
        }

        $idpFiles = $this->getIdPMetadataFiles();
        foreach($idpFiles as $file) {
            $output = $returnVal = null;
            exec('php -l ' . $file, $output, $returnVal);
            $this->assertEquals(
                0,
                $returnVal,
                'Lint test failed for file: ' . $file . '. Error: ' . print_r($output, true)
            );
        }
    }

    public function testMetadataFilesReturnArrays()
    {
        $spFiles = $this->getSpMetadataFiles();
        foreach($spFiles as $file) {
            $returnVal = include $file;
            $this->assertTrue(is_array($returnVal), 'Metadata file does not return array as expected. File: ' . $file);
        }

        $idpFiles = $this->getIdPMetadataFiles();
        foreach($idpFiles as $file) {
            $returnVal = include $file;
            $this->assertTrue(is_array($returnVal), 'Metadata file does not return array as expected. File: ' . $file);
        }
    }


    public function testIDPRemoteMetadataIDPCode()
    {
        $idpEntries = Metadata::getIdpMetadataEntries($this->metadataPath);
        $idpCode = 'IDPCode';

        foreach($idpEntries as $entityId => $entry) {
            $this->assertTrue(isset($entry[$idpCode]), 'Metadata entry does not include an ' . $idpCode .
                                                  ' element as expected. IDP: ' . $entityId);
            $this->assertTrue(is_string($entry[$idpCode]), 'Metadata entry has an IDPCode element that is not ' .
                                                     'a string. IDP: ' . $entityId);
            $this->assertRegExp("/^[A-Za-z0-9_-]+$/", $entry[$idpCode], 'Metadata entry has an ' .
                                $idpCode .' element that has something other than letters, ' .
                                'numbers, hyphens and underscores. IDP: ' . $entityId);
        }
    }

    public function testMetadataNoDuplicateEntities()
    {
        $entities = [];
        $spFiles = $this->getSpMetadataFiles();
        foreach($spFiles as $file) {
            $returnVal = include $file;
            foreach ($returnVal as $entityId => $entity) {
                $this->assertFalse(
                    in_array($entityId, $entities),
                    'Duplicate entity id found in metadata file: ' . $file . '. Entity ID: ' . $entityId
                );
                $entities[] = $entityId;
            }
        }

        $idpFiles = $this->getIdPMetadataFiles();
        foreach($idpFiles as $file) {
            $returnVal = include $file;
            foreach ($returnVal as $entityId => $entity) {
                $this->assertFalse(
                    in_array($entityId, $entities),
                    'Duplicate entity id found in metadata file: ' . $file . '. Entity ID: ' . $entityId
                );
                $entities[] = $entityId;
            }
        }
    }

    public function testMetadataNoSpsWithoutAnIdp()
    {
        $spEntries = Metadata::getSpMetadataEntries($this->metadataPath);

        $badSps = [];
        foreach($spEntries as $spEntityId => $spEntry) {
            $results = DiscoUtils::getIdpsForSp(
                $spEntityId,
                $this->metadataPath
            );

            if (empty($results)) {
                $badSps[] = $spEntityId;
            }
        }

        $this->assertTrue(empty($badSps),
            "At least one SP does not have an IdP it is allowed to use ... " .
            var_export($badSps, True));
    }

    public function testMetadataBadIdpName()
    {
        $idpEntries = Metadata::getIdpMetadataEntries($this->metadataPath);

        $badNames = [];

        foreach($idpEntries as $idpEntityId => $idpEntry) {
            if (empty($idpEntry['name']['en'])) {
                $badNames[] = $idpEntityId;
            }
        }

        $this->assertTrue(empty($badNames),
            "The following Idp's do not have a 'name' entry as an array with an 'en' entry ... " .
            var_export($badNames, True));
    }

    public function testMetadataMissingLogoURL()
    {
        $idpEntries = Metadata::getIdpMetadataEntries($this->metadataPath);

        $badLogos = [];

        foreach($idpEntries as $idpEntityId => $idpEntry) {
            if (empty($idpEntry['logoURL'])) {
                $badLogos[] = $idpEntityId;
            }
        }

        $this->assertTrue(empty($badLogos),
            "The following Idp's do not have a 'logoURL' entry ... " .
            var_export($badLogos, True));
    }

    public function testMetadataSPWithBadIDPList()
    {
        $idpListKey = Utils::IDP_LIST_KEY;
        $idpEntries = Metadata::getIdpMetadataEntries($this->metadataPath);
        $spEntries = Metadata::getSpMetadataEntries($this->metadataPath);

        $badSps = [];

        foreach ($spEntries as $spEntityId => $spEntry) {
            $nextBad = [];
            if ( ! empty($spEntry[$idpListKey])) {
                foreach ($spEntry[$idpListKey] as $nextIdp) {
                    if ( empty($idpEntries[$nextIdp])) {
                        $nextBad[] = $nextIdp;
                    }
                }
                if ( ! empty($nextBad)) {
                    $badSps[$spEntityId] = $nextBad;
                }
            }
        }

        $this->assertTrue(empty($badSps),
            'At least one SP has an IDPList with a bad IDP entity id ... ' . var_export($badSps, True));

    }

    public function testMetadataSPWithNoIDPList()
    {
        $idpListKey = Utils::IDP_LIST_KEY;
        $idpEntries = Metadata::getIdpMetadataEntries($this->metadataPath);
        $spEntries = Metadata::getSpMetadataEntries($this->metadataPath);

        $badSps = [];

        foreach ($spEntries as $spEntityId => $spEntry) {
            if ( ! isset($spEntry[$idpListKey])) {
                $badSps[] = $spEntityId;
            }
        }

        $this->assertTrue(empty($badSps),
            'At least one SP is missing an IDPList entry (required) ... ' . 
            var_export($badSps, True));
    }

    public function testMetadataCerts()
    {
        $spEntries = Metadata::getSpMetadataEntries($this->metadataPath);

        $badSps = [];

        foreach ($spEntries as $spEntityId => $spEntry) {
            if (empty($spEntry['certData']) && empty($spEntry['certFingerprint'])) {
                $badSps[] = $spEntityId;
            }
        }

        $this->assertTrue(empty($badSps),
            'At least one SP has neither a certData or certFingerprint entry ... ' .
            var_export($badSps, True));

    }

    public function testMetadataSignResponse()
    {
       // $this->markTestSkipped('Disabled for testing/verification');
        $spEntries = Metadata::getSpMetadataEntries($this->metadataPath);

        $badSps = [];
        $skippedSps = [];

        foreach ($spEntries as $spEntityId => $spEntry) {
            if ( ! empty($spEntry[self::SkipTestsKey])) {
                $skippedSps[] = $spEntityId;
                continue;
            }

            if (isset($spEntry['saml20.sign.response']) &&
                $spEntry['saml20.sign.response'] === False) {
                $badSps[] = $spEntityId;
            }
        }

        $this->assertTrue(empty($badSps),
            'At least one SP has saml20.sign.response set to false ... ' .
            var_export($badSps, True));

        if ($skippedSps) {
           $this->markTestSkipped('At least one SP had the ' . self::SkipTestsKey .
               ' metadata entry set ... ' . var_export($skippedSps, True));
        }
    }

    public function testMetadataSignAssertion()
    {
       // $this->markTestSkipped('Disabled for testing/verification');
        $spEntries = Metadata::getSpMetadataEntries($this->metadataPath);

        $badSps = [];
        $skippedSps = [];

        foreach ($spEntries as $spEntityId => $spEntry) {
            if ( ! empty($spEntry[self::SkipTestsKey])) {
                $skippedSps[] = $spEntityId;
                continue;
            }

            if (isset($spEntry['saml20.sign.assertion']) &&
                $spEntry['saml20.sign.assertion'] === False) {
                $badSps[] = $spEntityId;
            }
        }

        $this->assertTrue(empty($badSps),
            'At least one SP has saml20.sign.assertion set to false ... ' .
            var_export($badSps, True));

        if ($skippedSps) {
           $this->markTestSkipped('At least one SP had the ' . self::SkipTestsKey .
               ' metadata entry set ... ' . var_export($skippedSps, True));
        }
    }

    public function testMetadataEncryption()
    {
        // $this->markTestSkipped('Wait until we require encryption.');
        $spEntries = Metadata::getSpMetadataEntries($this->metadataPath);

        $badSps = [];
        $skippedSps = [];
        
        foreach ($spEntries as $spEntityId => $spEntry) {
            if ( ! empty($spEntry[self::SkipTestsKey])) {
                $skippedSps[] = $spEntityId;
                continue;
            }

            if (empty($spEntry['assertion.encryption'])) {
                $badSps[] = $spEntityId;
            }
        }

        $this->assertTrue(empty($badSps),
            'At least one SP does not have assertion.encryption set to True ... ' .
            var_export($badSps, True));

        if ($skippedSps) {
           $this->markTestSkipped('At least one SP had the ' . self::SkipTestsKey .
               ' metadata entry set ... ' . var_export($skippedSps, True));
        }
    }

    public function getSpMetadataFiles()
    {
        return $this->getFileList('sp');
    }

    public function getIdPMetadataFiles()
    {
        return $this->getFileList('idp');
    }

    public function getFileList($prefix)
    {
        return Metadata::getMetadataFiles($this->metadataPath, $prefix);
    }
}