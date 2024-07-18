#!/usr/bin/env bash

# echo script commands to stdout
set -x

# exit if any command fails
set -e

/data/run-metadata-tests.sh

./vendor/bin/phpunit -v tests/AnnouncementTest.php
./vendor/bin/phpunit -v vendor/simplesamlphp/simplesamlphp/modules/sildisco/tests/

/data/run-integration-tests.sh
