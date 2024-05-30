<?php
namespace SimpleSAML\Module\silauth\Auth\Source\tests\unit\http;

use SimpleSAML\Module\silauth\Auth\Source\http\Request;

class DummyRequest extends Request
{
    protected $dummyIpAddress;
    
    /**
     * Get the DUMMY IP address (as the single entry in an array).
     *
     * @return string[] A list containing the dummy IP address.
     */
    public function getIpAddresses()
    {
        return [$this->dummyIpAddress];
    }
    
    public function setDummyIpAddress($dummyIpAddress)
    {
        if ( ! self::isValidIpAddress($dummyIpAddress)) {
            throw new \InvalidArgumentException(sprintf(
                '%s is not a valid IP address.',
                var_export($dummyIpAddress, true)
            ));
        }
        
        $this->dummyIpAddress = $dummyIpAddress;
    }
}
