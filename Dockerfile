FROM silintl/php8:8.1

LABEL maintainer="gtis_itse@groups.sil.org"

RUN apt-get update -y \
    && apt-get --no-install-recommends install -y php-gmp \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /data

COPY dockerbuild/vhost.conf /etc/apache2/sites-enabled/
COPY dockerbuild/run.sh /data/run.sh
COPY dockerbuild/run-idp.sh /data/run-idp.sh
COPY dockerbuild/run-spidplinks.php /data/run-spidplinks.php
COPY dockerbuild/apply-dictionaries-overrides.php /data/

# Note the name change: repos extending this one should only run the metadata
# tests, so those are the only tests we make available to them.
COPY dockerbuild/run-metadata-tests.sh /data/run-tests.sh
COPY tests/MetadataTest.php /data/tests/MetadataTest.php

# ErrorLog inside a VirtualHost block is ineffective for unknown reasons
RUN sed -i -E 's@ErrorLog .*@ErrorLog /proc/1/fd/2@i' /etc/apache2/apache2.conf

# Install/cleanup composer dependencies
ARG COMPOSER_FLAGS="--prefer-dist --no-interaction --no-dev --optimize-autoloader --no-scripts --no-progress"
COPY composer.json /data/
COPY composer.lock /data/
RUN composer self-update --no-interaction
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install $COMPOSER_FLAGS

ENV SSP_PATH=/data/vendor/simplesamlphp/simplesamlphp

# Copy modules into simplesamlphp
COPY modules/ $SSP_PATH/modules

# Copy material theme templates to other modules, just in case the "default" theme is selected
COPY modules/material/themes/material/default/* $SSP_PATH/modules/default/templates/
COPY modules/material/themes/material/expirychecker/* $SSP_PATH/modules/expirychecker/templates/
COPY modules/material/themes/material/mfa/* $SSP_PATH/modules/mfa/templates/
COPY modules/material/themes/material/profilereview/* $SSP_PATH/modules/profilereview/templates/
COPY modules/material/themes/material/silauth/* $SSP_PATH/modules/silauth/templates/

# Copy in SSP override files
COPY dockerbuild/ssp-overrides/saml20-idp-remote.php $SSP_PATH/metadata/saml20-idp-remote.php
COPY dockerbuild/ssp-overrides/saml20-sp-remote.php $SSP_PATH/metadata/saml20-sp-remote.php
COPY dockerbuild/config/* $SSP_PATH/config/
COPY dockerbuild/ssp-overrides/sp-php.patch sp-php.patch
RUN patch /data/vendor/simplesamlphp/simplesamlphp/modules/saml/src/Auth/Source/SP.php sp-php.patch

ADD https://github.com/silinternational/config-shim/releases/latest/download/config-shim.gz config-shim.gz
RUN gzip -d config-shim.gz && chmod 755 config-shim && mv config-shim /usr/local/bin

# Set permissions for cache directory. Corresponds to the `cachedir` setting in config.php.
RUN mkdir /data/cache
RUN chown -R www-data:www-data /data/cache

EXPOSE 80
CMD ["/data/run.sh"]
