FROM silintl/php7:7.2

MAINTAINER Phillip Shipley <phillip.shipley@gmail.com>

ENV REFRESHED_AT 2019-09-04

RUN apt-get update -y && \
    apt-get install -y php-memcache php-gmp && \
    apt-get clean

# Create required directories
RUN mkdir -p /data

COPY dockerbuild/vhost.conf /etc/apache2/sites-enabled/
COPY dockerbuild/setup-logentries.sh /data/setup-logentries.sh
COPY dockerbuild/run.sh /data/run.sh
COPY dockerbuild/run-idp.sh /data/run-idp.sh
COPY dockerbuild/run-tests.sh /data/run-tests.sh
COPY dockerbuild/run-spidplinks.php /data/run-spidplinks.php

# Copy in syslog config
RUN rm -f /etc/rsyslog.d/*
COPY dockerbuild/rsyslog.conf /etc/rsyslog.conf

# get s3-expand
RUN curl https://raw.githubusercontent.com/silinternational/s3-expand/1.5/s3-expand -o /usr/local/bin/s3-expand \
    && chmod a+x /usr/local/bin/s3-expand

WORKDIR /data

# Install/cleanup composer dependencies
COPY composer.json /data/
COPY composer.lock /data/
RUN composer self-update --no-interaction
RUN composer install --prefer-dist --no-interaction --no-dev --optimize-autoloader --no-scripts --no-progress

# Copy in SSP override files
ENV SSP_PATH /data/vendor/simplesamlphp/simplesamlphp
RUN mv $SSP_PATH/www/index.php $SSP_PATH/www/ssp-index.php
COPY dockerbuild/ssp-overrides/index.php $SSP_PATH/www/index.php
RUN mv $SSP_PATH/www/saml2/idp/SingleLogoutService.php $SSP_PATH/www/saml2/idp/ssp-SingleLogoutService.php
COPY dockerbuild/ssp-overrides/SingleLogoutService.php $SSP_PATH/www/saml2/idp/SingleLogoutService.php
COPY dockerbuild/ssp-overrides/saml20-idp-remote.php $SSP_PATH/metadata/saml20-idp-remote.php
COPY dockerbuild/ssp-overrides/saml20-sp-remote.php $SSP_PATH/metadata/saml20-sp-remote.php
COPY dockerbuild/ssp-overrides/config.php $SSP_PATH/config/config.php
COPY dockerbuild/ssp-overrides/id.php $SSP_PATH/www/id.php
COPY dockerbuild/ssp-overrides/announcement.php $SSP_PATH/announcement/announcement.php
COPY tests /data/tests

RUN cp $SSP_PATH/modules/sildisco/sspoverrides/www_saml2_idp/SSOService.php $SSP_PATH/www/saml2/idp/
RUN chmod a+x /data/setup-logentries.sh /data/run.sh /data/run-tests.sh

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/s3-expand"]
CMD ["/data/run.sh"]
