#!/usr/bin/env bash

# echo script commands to stdout
set -x

# exit if any command fails
set -e

# Try to run database migrations
cd /data/vendor/simplesamlphp/simplesamlphp/modules/silauth/lib/Auth/Source
chmod a+x ./yii

./yii migrate --interactive=0

cd /data
./run.sh
