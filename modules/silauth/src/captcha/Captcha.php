<?php
namespace Sil\SilAuth\captcha;

use Sil\SilAuth\http\Request;

class Captcha
{
    private $secret;
    
    public function __construct($secret = null)
    {
        $this->secret = $secret;
    }
    
    public function isValidIn(Request $request)
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
