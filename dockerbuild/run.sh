#!/usr/bin/env bash

./setup-logentries.sh

# Run apache in foreground
apache2ctl -D FOREGROUND
