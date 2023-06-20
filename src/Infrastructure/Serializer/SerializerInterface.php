<?php

declare(strict_types = 1);

namespace App\Infrastructure\Serializer;

use Symfony\Component\Serializer\Exception\ExceptionInterface;

interface SerializerInterface
{
    public static function create(): self;

    /**
     * @throws ExceptionInterface
     */
    public function toArray(object $object): array;

    public function toJson(object $object): string;
}
