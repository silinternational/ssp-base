<?php
namespace Sil\IdPHubTests;

include __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Sil\SspUtils\Metadata;
use Sil\PhpEnv\Env;

class MetadataTest extends TestCase
{
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