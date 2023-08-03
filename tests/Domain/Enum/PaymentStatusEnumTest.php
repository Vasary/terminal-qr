<?php

declare(strict_types = 1);

namespace App\Tests\Domain\Enum;

use App\Domain\Enum\PaymentStatusEnum;

it('Enum should have cases', function () {
    $expectedCases = [
        [
            'name' => 'new',
            'value' => 0,
        ],
        [
            'name' => 'token',
            'value' => 1,
        ],
        [
            'name' => 'registered',
            'value' => 2,
        ],
        [
            'name' => 'awaiting',
            'value' => 3,
        ],
        [
            'name' => 'successfully',
            'value' => 4,
        ],
        [
            'name' => 'failure',
            'value' => 5,
        ],
        [
            'name' => 'timeout',
            'value' => 6,
        ],
        [
            'name' => 'unknown',
            'value' => 1000,
        ],
    ];

    $enum = PaymentStatusEnum::cases();

    $cases = [];
    foreach ($enum as $case) {
        $cases[] = [
            'name' => $case->name,
            'value' => $case->value,
        ];
    }

    expect($cases)->toEqual($expectedCases);
});

it('Enum has conditional methods', function (PaymentStatusEnum $enum, array $methods) {
    foreach ($methods as $method => $result) {
        expect($result)->toEqual($enum->$method())->and($enum->status())->toEqual($enum->name);
    }
})->with(
    [
        'new' => [
            'case' => PaymentStatusEnum::new,
            'methods' => [
                'isNew' => true,
                'isTokenized' => false,
                'isRegistered' => false,
                'isAwaiting' => false,
            ],
        ],
        'token' => [
            'case' => PaymentStatusEnum::token,
            'methods' => [
                'isNew' => false,
                'isTokenized' => true,
                'isRegistered' => false,
                'isAwaiting' => false,
            ],
        ],
        'registered' => [
            'case' => PaymentStatusEnum::registered,
            'methods' => [
                'isNew' => false,
                'isTokenized' => false,
                'isRegistered' => true,
                'isAwaiting' => false,
            ],
        ],
        'awaiting' => [
            'case' => PaymentStatusEnum::awaiting,
            'methods' => [
                'isNew' => false,
                'isTokenized' => false,
                'isRegistered' => false,
                'isAwaiting' => true,
            ],
        ],
    ]
);
