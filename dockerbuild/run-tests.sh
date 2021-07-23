#!/usr/bin/env bash

set -e
set -x

cd /data
export COMPOSER_ALLOW_SUPERUSER=1; composer install

./vendor/bin/phpunit -v tests/

whenavail "ssp-hub.local" 80 30 echo Hub ready
whenavail "ssp-hub-idp.local" 80 30 echo IDP 1 ready
whenavail "ssp-hub-sp.local" 80 30 echo SP 1 ready

./vendor/bin/behat \
    --append-snippets \
    --snippets-for=FeatureContext \
    --no-interaction \
    --stop-on-failure \
    --strict
