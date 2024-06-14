<?php
namespace SimpleSAML\Module\silauth\Auth\Source\captcha;

use SimpleSAML\Module\silauth\Auth\Source\http\Request;

class Captcha
{
    private ?string $secret;
    
    public function __construct(?string $secret = null)
    {
        $this->secret = $secret;
    }
    
    public function isValidIn(Request $request): bool
    {
        if (empty($this->secret)) {
            throw new \RuntimeException('No captcha secret available.', 1487342411);
        }
        
        $captchaResponse = $request->getCaptchaResponse();
        $ipAddress = $request->getMostLikelyIpAddress();
        
        $recaptcha = new \ReCaptcha\ReCaptcha($this->secret);
        $rcResponse = $recaptcha->verify($captchaResponse, $ipAddress);
        
        return $rcResponse->isSuccess();
    }
}
