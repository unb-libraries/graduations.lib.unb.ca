FROM unblibraries/drupal:alpine-nginx-php7-8.x-composer
MAINTAINER UNB Libraries <libsupport@unb.ca>

LABEL name="honorary.lib.unb.ca"
LABEL vcs-ref=""
LABEL vcs-url="https://github.com/unb-libraries/honorary.lib.unb.ca"

ARG COMPOSER_DEPLOY_DEV=no-dev

# Universal environment variables.
ENV DEPLOY_ENV prod
ENV DRUPAL_DEPLOY_CONFIGURATION TRUE
ENV DRUPAL_SITE_ID honorary
ENV DRUPAL_SITE_URI honorary.lib.unb.ca
ENV DRUPAL_SITE_UUID 52bca593-2542-4a35-9ebd-1da0d40bf67b
ENV DRUPAL_CONFIGURATION_EXPORT_SKIP devel

# Newrelic.
ENV NEWRELIC_PHP_VERSION 7.2.0.191
ENV NEWRELIC_PHP_ARCH musl

# Add Mail Sending
RUN apk --update add postfix && \
  rm -f /var/cache/apk/*
COPY package-conf/postfix/main.cf /etc/postfix/main.cf

RUN apk update && apk --update add php7-ldap@testing && \
#  ln -s /usr/lib/php7/ldap.so /usr/lib/php7.1/ldap.so && \
  rm -f /var/cache/apk/* && \
  echo "TLS_REQCERT never" > /etc/openldap/ldap.conf

# Add nginx and PHP conf.
COPY package-conf/nginx/app.conf /etc/nginx/conf.d/app.conf
COPY package-conf/php/app-php.ini /etc/php7/conf.d/zz_app.ini
COPY package-conf/php/app-php-fpm.conf /etc/php7/php-fpm.d/zz_app.conf

# Scripts.
COPY ./scripts/container /scripts
RUN curl -OL http://github.com/unb-libraries/docker-drupal-scripts/archive/container.zip && \
  unzip container.zip && \
  mv docker-drupal-scripts-container/*.sh /scripts/ && \
  rm -rf container.zip docker-drupal-scripts-container

# Remove upstream build and replace it with ours.
RUN /scripts/deleteUpstreamTree.sh
COPY build/ ${TMP_DRUPAL_BUILD_DIR}

# Deploy the generalized profile and makefile into our specific one.
RUN /scripts/deployGeneralizedProfile.sh

# Build Drupal tree.
ENV DRUPAL_BUILD_TMPROOT ${TMP_DRUPAL_BUILD_DIR}/webroot
RUN /scripts/buildDrupalTree.sh

# Install Newrelic.
RUN /scripts/installNewRelic.sh

# Copy configuration.
COPY ./config-yml ${TMP_DRUPAL_BUILD_DIR}/config-yml

# Custom modules not tracked in github.
COPY ./custom/modules ${TMP_DRUPAL_BUILD_DIR}/custom_modules
COPY ./custom/themes ${TMP_DRUPAL_BUILD_DIR}/custom_themes

# Tests
COPY ./tests/behat.yml ${TMP_DRUPAL_BUILD_DIR}/behat.yml
COPY ./tests/features ${TMP_DRUPAL_BUILD_DIR}/features
