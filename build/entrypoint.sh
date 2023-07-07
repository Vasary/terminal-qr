#!/bin/sh

set -e

/usr/local/bin/docker-php-entrypoint

stat() {
  printf "[ %s ] %s\n" "$2" "$1"

  if [ "$1" = "Fail" ]; then
    exit 1
  fi
}

if [ ! -d /app/var ]; then
  mkdir -p /app/var > /dev/null 2>&1 && stat "Var directory created" "OK" || stat "Failed to create var directory" "FAIL"
  chmod 0777 -R /app/var > /dev/null 2>&1 && stat "Fix var directory chmod" "OK" || stat "Failed to change var directory chmod" "FAIL"
fi

/app/bin/console cache:warmup > /dev/null 2>&1 && stat "Warmup cache" "OK" || stat "Failed to warmup cache" "FAIL"
chown www-data:www-data -R /app/var > /dev/null 2>&1 && stat "Fix var chown" "OK" || stat "Failed to change var directory owner" "FAIL"

if [ ! -f "resource/production.db" ]; then
    /app/bin/console do:da:cr > /dev/null 2>&1 && stat "Creating empty database" "OK" || stat "Failed to create database" "FAIL"
    /app/bin/console do:sc:up --force --complete > /dev/null 2>&1 && stat "Update database schema" "OK" || stat "Failed to update database schema" "FAIL"
    /app/bin/console do:fi:lo --no-interaction > /dev/null 2>&1 && stat "Load default fixtures" "OK" || stat "Failed to load default fixtures" "FAIL"
else
    stat "Database existing" "OK"
    /app/bin/console do:sc:up --force > /dev/null 2>&1 && stat "Update database schema" "OK" || stat "Failed to update database schema" "FAIL"
fi

chown www-data:www-data /app/resource/*.db && stat "Update database permissions" "OK" || stat "Failed to update database permissions" "FAIL"

php-fpm -D > /dev/null 2>&1 && stat "Running PHP-FPM" "OK" || stat "Failed to run PHP-FPM" "FAIL"

stat "Preparation done" "OK"

exec "$@"
