#!/usr/bin/env sh

sed -i 's@^\( *'\''module\.enable'\'' => \[\)@\1'\''\n        exampleauth'\'' => true,@' vendor/simplesamlphp/simplesamlphp/config/config.php
