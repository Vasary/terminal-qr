<?php

declare(strict_types = 1);

use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Credentials\SPB;
use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Host;
use App\Domain\ValueObject\Key;
use App\Domain\ValueObject\Portal;
use App\Domain\ValueObject\Title;
use App\Infrastructure\Persistence\Doctrine\Type\CredentialType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

it('Credential have to return json string as database value', function () {
    $type = new CredentialType();

    $mock = Mockery::mock(AbstractPlatform::class);
    $credential = new SPB(
        new Title(faker()->title()),
        new Callback(faker()->url()),
        new Host(faker()->domainName()),
        new Portal(faker()->lexify('??????')),
        new Currency(faker()->currencyCode()),
        new Key(faker()->lexify('???'))
    );

    $result = $type->convertToDatabaseValue($credential, $mock);



//    expect($result)->
});
