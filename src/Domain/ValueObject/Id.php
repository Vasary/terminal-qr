<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use App\Domain\Factory\UUID\Builder\Builder;
use App\Domain\Factory\UUID\Generator\SimpleGenerator;
use App\Domain\Factory\UUID\IdFactory;
use App\Domain\Factory\UUID\IdFactoryInterface;
use Stringable;

final class Id implements Stringable
{
    private static ?IdFactoryInterface $factory = null;

    public static function fromString(string $id): self
    {
        return new self(self::getFactory()->fromString($id));
    }

    public static function create(): self
    {
        return new self(self::getFactory()->v4());
    }

    private function __construct(private readonly Uuid $id)
    {
    }

    public static function getFactory(): IdFactoryInterface
    {
        if (null === self::$factory) {
            self::$factory = new IdFactory(
                new SimpleGenerator(),
                new Builder(),
            );
        }

        return self::$factory;
    }

    public static function setFactory(?IdFactoryInterface $factory): void
    {
        self::$factory = $factory;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
