#!/bin/sh

if ! whoami &> /dev/null; then
  if [ -w /etc/passwd ]; then
    echo "${USER_NAME:-default}:x:$(id -u):0:${USER_NAME:-default} user:${HOME}:/sbin/nologin" >> /etc/passwd
  fi
fi

sed -i 's/__APP_HOST__/'"$APP_HOST"'/g' /etc/nginx/conf.d/default.conf

exec "$@"
