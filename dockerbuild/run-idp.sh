#!/usr/bin/env bash

# Try to run database migrations
cd /data/vendor/simplesamlphp/simplesamlphp/modules/silauth
chmod a+x ./src/yii
./src/yii migrate --interactive=0

# If they failed, exit.
rc=$?; if [[ $rc != 0 ]]; then exit $rc; fi

cd /data
./run.sh