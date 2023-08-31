<?php

declare(strict_types = 1);

namespace App\Infrastructure\Serializer\Attribute;

use Attribute;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap as Base;

#[Attribute(Attribute::TARGET_CLASS)]
final class DiscriminatorMap extends Base
{
}
