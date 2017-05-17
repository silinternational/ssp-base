#!/usr/bin/env bash

# Try to run database migrations
cd /data/vendor/simplesamlphp/simplesamlphp/modules/silauth
chmod a+x ./src/yii

output=$(./src/yii migrate --interactive=0 2>&1)

# If they failed, exit.
rc=$?;
if [[ $rc != 0 ]]; then
    logger --priority user.err --stderr "Migrations failed with status ${rc} and output: ${output}"
    exit $rc;
fi

cd /data
./run.sh