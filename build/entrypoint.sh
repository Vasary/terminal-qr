#!/bin/sh

set -e

/usr/local/bin/docker-php-entrypoint

stat() {
  printf "[ %s ] %s\n" "$2" "$1"

  if [ "$1" = "Fail" ]; then
    exit 1
  fi
}

if [ ! -e /app/resource/events.log ]; then
  touch /app/resource/events.log >/dev/null && stat "Events.log created" "OK" || stat "Failed to create events.log" "FAIL"
fi

if [ ! -d /app/var ]; then
  mkdir -p /app/var >/dev/null && stat "Var directory created" "OK" || stat "Failed to create var directory" "FAIL"
  chmod 0777 -R /app/var >/dev/null && stat "Fix var directory chown" "OK" || stat "Failed to change var directory permissions" "FAIL"
fi

/app/bin/console cache:warmup >/dev/null && stat "Warmup cache" "OK" || stat "Failed to warmup cache" "FAIL"

exec "$@"
