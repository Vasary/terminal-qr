#!/bin/sh

set -e

/usr/local/bin/docker-php-entrypoint

stat() {
  printf "[ %s ] %s\n" "$2" "$1"

  if [ "$1" = "Fail" ]; then
    exit 1
  fi
}

php-fpm -D

if [ ! -d /app/var ]; then
  mkdir -p /app/var >/dev/null && stat "Var directory created" "OK" || stat "Failed to create var directory" "FAIL"
  chmod 0777 -R /app/var >/dev/null && stat "Fix var directory chown" "OK" || stat "Failed to change var directory permissions" "FAIL"
fi

/app/bin/console cache:warmup >/dev/null && stat "Warmup cache" "OK" || stat "Failed to warmup cache" "FAIL"

if [ ! -f "resource/production.db" ]; then
    /app/bin/console do:da:cr > /dev/null && stat "Creating empty database" "OK" || stat "Failed to create database" "FAIL"
    /app/bin/console do:sc:up --force > /dev/null && stat "Update database schema" "OK" || stat "Failed to update database schema" "FAIL"
    /app/bin/console do:fi:lo --no-interaction > /dev/null && stat "Load default fixtures" "OK" || stat "Failed to load default fixtures" "FAIL"
else
    stat "Database existing" "OK"
    /app/bin/console do:sc:up --force > /dev/null && stat "Update database schema" "OK" || stat "Failed to update database schema" "FAIL"
fi

exec "$@"
