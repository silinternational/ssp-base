<?php
namespace SimpleSAML\Module\silauth\Auth\Source\traits;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

trait LoggerAwareTrait
{
    /** @var LoggerInterface */
    protected $logger;
    
    public function initializeLogger()
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
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
