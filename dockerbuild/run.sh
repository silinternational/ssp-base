#!/usr/bin/env bash

cd /data
./setup-logentries.sh

# Run apache in foreground
apache2ctl -D FOREGROUND
