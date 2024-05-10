#!/usr/bin/env bash

set -e
set -x

cd /data
export COMPOSER_ALLOW_SUPERUSER=1; composer install

whenavail "ssp-hub.local"     80 15 echo Hub ready
whenavail "ssp-idp1.local" 80 5 echo IDP 1 ready
whenavail "ssp-sp1.local"  80 5 echo SP 1 ready

./vendor/bin/behat \
    --no-interaction \
    --stop-on-failure
