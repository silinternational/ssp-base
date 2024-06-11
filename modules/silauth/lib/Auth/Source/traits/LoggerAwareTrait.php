<?php
namespace SimpleSAML\Module\silauth\Auth\Source\traits;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

trait LoggerAwareTrait
{
    /** @var LoggerInterface */
    protected LoggerInterface $logger;
    
    public function initializeLogger(): void
    {
        if (empty($this->logger)) {
            $this->logger = new NullLogger();
        }
    }
    
    /**
     * Set a logger for this class instance to use.
     *
     * @param LoggerInterface $logger A PSR-3 compliant logger.
     * @return null
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
