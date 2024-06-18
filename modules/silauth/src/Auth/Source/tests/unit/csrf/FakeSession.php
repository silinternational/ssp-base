<?php

namespace SimpleSAML\Module\silauth\Auth\Source\tests\unit\csrf;

use SimpleSAML\Session;

/**
 * Class to mimic the bare basics of the SimpleSAML\Session class in order to
 * allow good testing of the CsrfProtector class.
 */
class FakeSession extends Session
{
    private array $inMemoryDataStore;
    
    private function __construct(bool $transient = false)
    {
        $this->inMemoryDataStore = [];
    }
    
    public function getData(string $type, ?string $id): mixed
    {
        return $this->inMemoryDataStore[$type][$id] ?? null;
    }
    
    public static function getSessionFromRequest(): Session
    {
        return new self();
    }
    
    public function setData(string $type, string $id, mixed $data, int|string|null $timeout = null): void
    {
        // Make sure an array exists for that type of data.
        $this->inMemoryDataStore[$type] = $this->inMemoryDataStore[$type] ?? [];
        
        // Store the given data.
        $this->inMemoryDataStore[$type][$id] = $data;
    }
}
