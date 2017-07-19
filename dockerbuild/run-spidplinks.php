<?php

include "/data/vendor/autoload.php"; 
use Sil\SspUtils\DiscoUtils; 

$mdPath = "/data/vendor/simplesamlphp/simplesamlphp/metadata"; 

$results = DiscoUtils::listAllSpIdpLinks($mdPath, "nothtml");
 
echo $results["text"];