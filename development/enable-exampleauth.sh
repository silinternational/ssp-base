#!/usr/bin/env sh

sed -i 's@^\( *'\''module\.enable'\'' => \[\)@\1\n        '\''exampleauth'\'' => true,@' /data/vendor/simplesamlphp/simplesamlphp/config/config.php
