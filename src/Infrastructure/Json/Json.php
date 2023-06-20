<?php

declare(strict_types = 1);

namespace App\Infrastructure\Json;

use ArrayObject;

final class Json
{
    public static function decode(string $content): ArrayObject
    {
        $data = json_decode($content, true, 512, \JSON_THROW_ON_ERROR);

        return new ArrayObject($data);
    }
}
