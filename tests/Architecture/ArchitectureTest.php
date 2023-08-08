<?php

declare(strict_types = 1);

use Pest\Expectation;

describe('Architecture', function () {
    test('globals')
        ->expect(['dd', 'dump'])
        ->not->toBeUsed()
        ->ignoring(Expectation::class);

    test('app')
        ->expect('App\Domain\Model')
        ->toExtendNothing()
    ;

    test('contracts')
        ->expect('App\Application\Contract')
        ->toBeInterfaces();

    test('enums')
        ->expect('App\Domain\Enum')
        ->toBeEnums()
    ;
});
