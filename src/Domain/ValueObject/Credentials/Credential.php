<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\Credentials;

use App\Infrastructure\Serializer\Attribute\DiscriminatorMap;

#[DiscriminatorMap(typeProperty: 'type', mapping: [
    Stub::class => Stub::class,
    SPB::class => SPB::class,
])]
abstract class Credential
{
}
