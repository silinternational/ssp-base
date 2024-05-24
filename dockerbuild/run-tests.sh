#!/usr/bin/env bash

set -e
set -x

/data/run-metadata-tests.sh

./vendor/bin/phpunit -v tests/AnnouncementTest.php
./vendor/bin/phpunit -v tests/IdpDiscoTest.php

/data/run-integration-tests.sh
