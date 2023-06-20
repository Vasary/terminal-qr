<?php

declare(strict_types = 1);

require dirname(__DIR__).'/vendor/autoload.php';

$console = sprintf('%s/../bin/console', __DIR__);

passthru(
    <<<CMD
    php $console doctrine:database:drop --force --env=test && \
    php $console doctrine:database:create --no-interaction --env=test && \
    php $console doctrine:sc:up --force --complete
    CMD,
    $code
);

if ($code) {
    exit('Bootstrap: can\'t reload fixtures' . PHP_EOL);
}
