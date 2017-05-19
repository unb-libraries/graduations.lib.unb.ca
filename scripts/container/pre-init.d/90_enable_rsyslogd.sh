#!/usr/bin/env sh
if [ "$DEPLOY_ENV" == "prod" ]; then
  sed -i "s|LOGZIO_KEY|$LOGZIO_KEY|g" /etc/rsyslog.conf
  /usr/sbin/rsyslogd -f /etc/rsyslog.d/21-logzio-nginx.conf
fi
