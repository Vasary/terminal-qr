<?php

declare(strict_types = 1);

use Pest\Expectation;

test('globals')
    ->expect(['dd', 'dump'])
    ->not->toBeUsed()
    ->ignoring(Expectation::class);


test('contracts')
    ->expect('App\Application\Contract')
    ->toOnlyUse([
        'App\Application\Contract\ContainerInterface',
        'App\Application\Contract\HttpClientInterface',
        'App\Application\Contract\TranslatorInterface',
        'App\Application\Contract\UserPasswordHasherInterface',
    ])->toBeInterfaces();

test('app')
    ->expect('App\Domain\Model')
    ->toExtendNothing();
