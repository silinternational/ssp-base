#!/usr/bin/env bash

cd /data
export COMPOSER_ALLOW_SUPERUSER=1; composer install

# If that failed, exit.
rc=$?; if [[ $rc != 0 ]]; then exit $rc; fi

./vendor/bin/phpunit -v tests/
