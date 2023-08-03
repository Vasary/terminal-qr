<?php

declare(strict_types = 1);

use App\Infrastructure\Test\AbstractWebTestCase;

require dirname(__DIR__).'/vendor/autoload.php';
require '_helpers' . DIRECTORY_SEPARATOR . 'helpers.php';

dropDatabase();
createSchema();
runMigrations();

uses(AbstractWebTestCase::class)->in('Presentation/HealthCheck', 'Presentation/UI');
