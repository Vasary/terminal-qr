<?php

declare(strict_types = 1);

use App\Application\Payment\Business\PaymentProcessor\PaymentProcessor;
use App\Domain\Exception\NotFoundException;
use App\Domain\ValueObject\Id;

test('should fail when payment not found', function () {
    /** @var PaymentProcessor $facade */
    $facade = $this->getContainer()->get(PaymentProcessor::class);

    $this->expectException(NotFoundException::class);
    $this->expectExceptionMessage(
        'Entity with 9827fb34-8953-4c39-9bbc-13cacc9bc951 of App\Domain\Model\Payment not found'
    );

    $facade->handle(Id::fromString('9827fb34-8953-4c39-9bbc-13cacc9bc951'));
});
