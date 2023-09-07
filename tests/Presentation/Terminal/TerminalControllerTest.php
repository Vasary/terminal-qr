<?php

declare(strict_types = 1);

use App\Infrastructure\Test\AbstractWebTestCase;

uses(AbstractWebTestCase::class);

test('should successfully open terminal and register new payment', function () {
    $client = $this->createClient();

    // Request a specific page
    $crawler = $client->request('GET', '/');
});
