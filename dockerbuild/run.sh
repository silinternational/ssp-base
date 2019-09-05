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

# Send info about this image's O/S and PHP version to our logs.
cat /etc/*release | grep PRETTY | logger -p 1 -t run.warning
php -v | head -n 1 | logger -p 1 -t run.warning

apache2ctl start

# endless loop with a wait is needed for the trap to work
while true
do
  tail -f /dev/null & wait ${!}
done
