#!/usr/bin/env bash

set -e
set -x

cd /data
export COMPOSER_ALLOW_SUPERUSER=1; composer install

./vendor/bin/phpunit -v tests/

whenavail "ssp-hub.local" 80 30 echo Hub ready
./vendor/bin/behat \
    --append-snippets \
    --snippets-for=FeatureContext \
    --no-interaction \
    --stop-on-failure \
    --strict
