<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Faker;

use App\Domain\Model\Attribute;
use App\Domain\Model\Unit;
use App\Domain\ValueObject\I18N;
use Faker\Generator as Base;

/**
 * @method string uuidv4()
 * @method I18N localization(int $length)
 * @method Unit unit(?string $id = null)
 * @method Attribute attribute()
 */
final class Generator extends Base
{
}
