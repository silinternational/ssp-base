#!/usr/bin/env bash

set -e
set -x

cd /data
export COMPOSER_ALLOW_SUPERUSER=1; composer install

whenavail "ssp-hub.local"     80 10 echo Hub ready
whenavail "ssp-idp1.local" 80 10 echo IDP 1 ready
whenavail "ssp-sp1.local"  80 10 echo SP 1 ready

./vendor/bin/behat \
    --append-snippets \
    --snippets-for=FeatureContext \
    --no-interaction \
    --stop-on-failure #\
    #--strict
