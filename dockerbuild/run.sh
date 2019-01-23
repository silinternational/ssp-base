#!/usr/bin/env bash

trap "apache2ctl stop" SIGINT SIGTERM

cd /data
./setup-logentries.sh

# Run apache in foreground
apache2ctl -D FOREGROUND
