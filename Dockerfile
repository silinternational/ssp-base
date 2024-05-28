FROM silintl/php8:8.1

LABEL maintainer="Steve Bagwell <steve_bagwell@sil.org>"

ENV REFRESHED_AT 2021-06-14

RUN apt-get update -y \
    && apt-get install -y \
        php-gmp \
        php-memcached \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Create required directories
RUN mkdir -p /data

COPY dockerbuild/vhost.conf /etc/apache2/sites-enabled/
COPY dockerbuild/run.sh /data/run.sh
COPY dockerbuild/run-idp.sh /data/run-idp.sh
COPY dockerbuild/run-spidplinks.php /data/run-spidplinks.php
COPY dockerbuild/apply-dictionaries-overrides.php /data/

# Note the name change: repos extending this one should only run the metadata
# tests, so those are the only tests we make available to them.
COPY dockerbuild/run-metadata-tests.sh /data/run-tests.sh

# ErrorLog inside a VirtualHost block is ineffective for unknown reasons
RUN sed -i -E 's@ErrorLog .*@ErrorLog /proc/1/fd/2@i' /etc/apache2/apache2.conf

# get s3-expand
RUN curl https://raw.githubusercontent.com/silinternational/s3-expand/1.5/s3-expand -fo /usr/local/bin/s3-expand \
    && chmod a+x /usr/local/bin/s3-expand

WORKDIR /data

# Install/cleanup composer dependencies
COPY composer.json /data/
COPY composer.lock /data/
RUN composer self-update --no-interaction
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --prefer-dist --no-interaction --no-dev --optimize-autoloader --no-scripts --no-progress

ENV SSP_PATH /data/vendor/simplesamlphp/simplesamlphp

# Copy modules into simplesamlphp
COPY modules/ $SSP_PATH/modules

# Copy material theme templates to other modules, just in case the "default" theme is selected
COPY modules/material/themes/material/expirychecker/* $SSP_PATH/modules/expirychecker/templates/
COPY modules/material/themes/material/mfa/* $SSP_PATH/modules/mfa/templates/
COPY modules/material/themes/material/profilereview/* $SSP_PATH/modules/profilereview/templates/

# Copy in SSP override files
RUN mv $SSP_PATH/www/index.php $SSP_PATH/www/ssp-index.php
COPY dockerbuild/ssp-overrides/index.php $SSP_PATH/www/index.php
RUN mv $SSP_PATH/www/saml2/idp/SingleLogoutService.php $SSP_PATH/www/saml2/idp/ssp-SingleLogoutService.php
COPY dockerbuild/ssp-overrides/SingleLogoutService.php $SSP_PATH/www/saml2/idp/SingleLogoutService.php
COPY dockerbuild/ssp-overrides/saml20-idp-remote.php $SSP_PATH/metadata/saml20-idp-remote.php
COPY dockerbuild/ssp-overrides/saml20-sp-remote.php $SSP_PATH/metadata/saml20-sp-remote.php
COPY dockerbuild/config/* $SSP_PATH/config/
COPY dockerbuild/ssp-overrides/id.php $SSP_PATH/www/id.php
COPY dockerbuild/ssp-overrides/announcement.php $SSP_PATH/announcement/announcement.php
COPY tests /data/tests

RUN cp $SSP_PATH/modules/sildisco/lib/SSOService.php $SSP_PATH/www/saml2/idp/
RUN chmod a+x /data/run.sh /data/run-tests.sh

ADD https://github.com/silinternational/config-shim/releases/latest/download/config-shim.gz config-shim.gz
RUN gzip -d config-shim.gz && chmod 755 config-shim && mv config-shim /usr/local/bin

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/s3-expand"]
CMD ["/data/run.sh"]
