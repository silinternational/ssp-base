#!/usr/bin/env bash

echo "Installing php-xdebug"
apt-get update -y
apt-get install -y php-xdebug
phpenmod xdebug

INI_FILE="/etc/php/7.0/apache2/php.ini"
echo "Configuring debugger in $INI_FILE"
echo "xdebug.remote_enable=1" >> $INI_FILE
echo "xdebug.remote_host=$XDEBUG_REMOTE_HOST" >> $INI_FILE

mkdir -p /data/vendor/simplesamlphp/simplesamlphp/modules/sildisco

# now the builtin run script can be started
/data/run.sh
