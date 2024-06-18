#!/usr/bin/env bash

# echo script commands to stdout
set -x

# exit if any command fails
set -e

# Try to run database migrations
cd /data/vendor/simplesamlphp/simplesamlphp
chmod a+x ./modules/silauth/src/Auth/Source/yii

./modules/silauth/src/Auth/Source/yii migrate --interactive=0

cd /data
./run.sh
