<?php

include __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Sil\SspUtils\AnnouncementUtils;

class AnnouncementTest extends TestCase
{

    /**
     * Ensure the /data/ssp-announcement.php file can be included without an error
     */
    public function testGetSimpleAnnouncement()
    {
        $results = AnnouncementUtils::getSimpleAnnouncement();
    }

}