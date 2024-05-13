<?php
namespace Sil\SilAuth\tests\unit\captcha;

use Sil\SilAuth\captcha\Captcha;
use Sil\SilAuth\http\Request;

class DummySuccessfulCaptcha extends Captcha
{
    public function isValidIn(Request $request)
    {
        return true;
    }
}
