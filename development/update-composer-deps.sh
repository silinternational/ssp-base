#!/usr/bin/env bash

cd /data
composer self-update

# Update the composer dependencies.
composer update --no-scripts

# Make sure all our simplesamlphp modules are still there. (They can be removed
# from the vendor folder if simplesamlphp was updated and the modules weren't).
composer install --no-scripts --no-progress

# Update our list of what packages are currently installed.
composer show -D --format=json > installed-packages.json
