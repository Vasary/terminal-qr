<?php

declare(strict_types = 1);

use App\Domain\Enum\PaymentStatusEnum;

it('Enum should have cases', function () {
    $expectedCases = [
        [
            'name' => 'new',
            'value' => 0,
        ],
        [
            'name' => 'awaiting',
            'value' => 1,
        ],
        [
            'name' => 'successfully',
            'value' => 2,
        ],
        [
            'name' => 'failure',
            'value' => 3,
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
