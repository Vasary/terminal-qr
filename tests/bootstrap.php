<?php

declare(strict_types = 1);

use App\Infrastructure\Test\AbstractWebTestCase;

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/src/Infrastructure/Test/_helpers/helpers.php';

updateSchema();

uses(AbstractWebTestCase::class)->in('Application', 'Presentation', 'Infrastructure');
