#!/usr/bin/env bash

# establish a signal handler to catch the SIGTERM from a 'docker stop'
# reference: https://medium.com/@gchudnov/trapping-signals-in-docker-containers-7a57fdda7d86
term_handler() {
  apache2ctl stop
  exit 143; # 128 + 15 -- SIGTERM
}
trap 'kill ${!}; term_handler' SIGTERM

cd /data
./setup-logentries.sh

apache2ctl start

# endless loop with a wait is needed for the trap to work
while true
do
  tail -f /dev/null & wait ${!}
done
