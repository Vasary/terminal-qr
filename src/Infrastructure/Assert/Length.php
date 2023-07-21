<?php

declare(strict_types = 1);

namespace App\Infrastructure\Assert;

use Attribute;
use Symfony\Component\Validator\Constraints\Length as Base;

#[\Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Length extends Base
{
    public $maxMessage = 'Это значение слишком длинное. Оно должно иметь {{ limit }} символов или меньше.';
    public $minMessage = 'Это значение слишком короткое. Оно должно содержать {{ limit }} символов или больше.';
    public $exactMessage = 'Это значение должно содержать ровно {{ limit }} символов.';
    public $charsetMessage = 'Это значение не соответствует ожидаемой {{ charset }} кодировке.';
}
