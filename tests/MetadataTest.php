<?php
namespace Sil\IdPHubTests;

include __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Sil\PhpEnv\Env;
use Sil\SspUtils\Metadata;
use Sil\SspUtils\DiscoUtils;
use Sil\SspUtils\Utils;

class MetadataTest extends TestCase
{
    // SP Metadata entry to request that certain tests be skipped
    const SkipTestsKey = "SkipTests";

    const IdpCode = 'IDPNamespace';

    const SPNameKey = 'name';
    
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

        foreach($idpEntries as $entityId => $entry) {
            $this->assertTrue(isset($entry[self::IdpCode]), 'Metadata entry does not ' .
                'include an ' . self::IdpCode . ' element as expected. IDP: ' . $entityId);

            $nextCode = $entry[self::IdpCode];
            $this->assertTrue(is_string($nextCode), 'Metadata entry has an ' . 
                self::IdpCode . 'element that is not a string. IDP: ' . $entityId);
            $this->assertRegExp("/^[A-Za-z0-9_-]+$/", $nextCode, 'Metadata entry has an ' .
                self::IdpCode .' element that has something other than letters, ' .
                'numbers, hyphens and underscores. IDP: ' . $entityId);
        }
    }

    public function testIDPRemoteMetadataBadSPList()
    {
        $hubMode = Env::get('HUB_MODE', true);
        if ( ! $hubMode) {
            $this->markTestSkipped('Skipping test because HUB_MODE = false');
            return;
        }

        $badIdps = [];

        $idpEntries = Metadata::getIdpMetadataEntries($this->metadataPath);
        $spListKey = Utils::SP_LIST_KEY;

        foreach($idpEntries as $entityId => $entry) {
            if (isset($entry[$spListKey]) && ! is_array($entry[$spListKey])) {
                $badIdps[] = $entityId;
            }
        }

        $this->assertTrue(empty($badIdps),
            "At least one IdP has an " .
            $spListKey . " entry that is not an array ... " . PHP_EOL .
            var_export($badIdps, True));
    }

    public function testIDPRemoteMetadataBadSPListEntry()
    {
        $hubMode = Env::get('HUB_MODE', true);
        if ( ! $hubMode) {
            $this->markTestSkipped('Skipping test because HUB_MODE = false');
            return;
        }

        $spEntries = Metadata::getSpMetadataEntries($this->metadataPath);

        $badSps = [];

        $idpEntries = Metadata::getIdpMetadataEntries($this->metadataPath);
        $spListKey = Utils::SP_LIST_KEY;

        foreach($idpEntries as $entityId => $entry) {
            if (isset($entry[$spListKey]) && is_array($entry[$spListKey])) {
                foreach($entry[$spListKey] as $nextSp) {
                    if ( ! isset($spEntries[$nextSp])) {
                        $badSps[] = $nextSp;
                    }
                }
            }
        }

        $this->assertTrue(empty($badSps),
            "At least one non-existent SP is listed in an IdP's " .
            $spListKey . " entry ... " . PHP_EOL .
            var_export($badSps, True));
    }


    public function testIDPRemoteMetadataNoDuplicateIDPCode()
    {
        $idpEntries = Metadata::getIdpMetadataEntries($this->metadataPath);
        $codes = [];

        foreach($idpEntries as $entityId => $entry) {
            $nextCode = $entry[self::IdpCode];
            $this->assertFalse(in_array($nextCode, $codes),
                "Metadata has a duplicate " . self::IdpCode . " entry: " . $nextCode);
            $codes[] = $nextCode;
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
        $hubMode = Env::get('HUB_MODE', true);
        if ( ! $hubMode) {
            $this->markTestSkipped('Skipping test because HUB_MODE = false');
            return;
        }
        
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
        $hubMode = Env::get('HUB_MODE', true);
        if ( ! $hubMode) {
            $this->markTestSkipped('Skipping test because HUB_MODE = false');
            return;
        }
        $idpListKey = Utils::IDP_LIST_KEY;
        $spEntries = Metadata::getSpMetadataEntries($this->metadataPath);

        $badSps = [];

        foreach ($spEntries as $spEntityId => $spEntry) {
            if (empty($spEntry[$idpListKey])) {
                $badSps[] = $spEntityId;
            }
        }

        $this->assertTrue(empty($badSps),
            'At least one SP has an empty IDPList entry (required) ... ' . 
            var_export($badSps, True));
    }

    public function testMetadataSPWithNoName()
    {
        $hubMode = Env::get('HUB_MODE', true);
        if ( ! $hubMode) {
            $this->markTestSkipped('Skipping test because HUB_MODE = false');
            return;
        }

        $spEntries = Metadata::getSpMetadataEntries($this->metadataPath);

        $badSps = [];

        foreach ($spEntries as $spEntityId => $spEntry) {
            if (empty($spEntry[self::SPNameKey])) {
                $badSps[] = $spEntityId;
            }
        }

        $this->assertTrue(empty($badSps),
            'At least one SP has an empty "' . self::SPNameKey . '" entry (required) ... ' .
            var_export($badSps, True));
    }

    public function testMetadataWithBadEnabled()
    {
        $idpEntries = Metadata::getIdpMetadataEntries($this->metadataPath);
        $enabledKey = 'enabled';
        $badEnabled = [];

        foreach($idpEntries as $idpEntityId => $idpEntry) {
            if ( ! isset($idpEntry[$enabledKey]) || 
                 ! is_bool($idpEntry[$enabledKey])) {
                $badEnabled[] = $idpEntityId;
            } 
        }
        
        $this->assertTrue(empty($badEnabled),
            "The following Idp's do not have a boolean '" . $enabledKey . "' entry ... " .
            var_export($badEnabled, True));
    }


    public function testMetadataCerts()
    {
        $spEntries = Metadata::getSpMetadataEntries($this->metadataPath);

        $badSps = [];

        foreach ($spEntries as $spEntityId => $spEntry) {

            if ( ! empty($spEntry[self::SkipTestsKey])) {
                continue;
            }

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