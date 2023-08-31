<?php

declare(strict_types = 1);

use App\Domain\ValueObject\Credentials\SPB;
use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Host;
use App\Domain\ValueObject\Portal;
use App\Infrastructure\Persistence\Doctrine\Type\CredentialType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

test('credential have to return json string as database value', function () {
    $type = new CredentialType();

    $mock = Mockery::mock(AbstractPlatform::class);
    $credential = new SPB(
        new Host(faker()->domainName()),
        new Portal(faker()->lexify('??????')),
        new Currency(faker()->currencyCode()),
    );

    $result = $type->convertToDatabaseValue($credential, $mock);

    expect($result)->toBeJson();

    $content = json_decode($result, true);

    expect($content)->toHaveKeys(['host', 'portal', 'currency', 'type'])
        ->and($content['host'])->toEqual((string) $credential->host())
        ->and($content['portal'])->toEqual((string) $credential->portal())
        ->and($content['currency'])->toEqual((string) $credential->currency());
});

test('credentials object should build successfully', function () {
    $type = new CredentialType();
    $mock = Mockery::mock(AbstractPlatform::class);

    $data = [
        'type' => SPB::class,
        'host' => faker()->domainName(),
        'portal' => faker()->lexify('??????'),
        'currency' => faker()->currencyCode(),
    ];

    /** @var SPB $result */
    $result = $type->convertToPHPValue(json_encode($data), $mock);

    expect($result)->toBeInstanceOf(SPB::class)
        ->and((string) $result->host())->toEqual($data['host'])
        ->and((string) $result->portal())->toEqual($data['portal'])
        ->and((string) $result->currency())->toEqual($data['currency']);
});
