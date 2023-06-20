<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Faker;

use App\Infrastructure\Test\Faker\Provider\AttributeProvider;
use App\Infrastructure\Test\Faker\Provider\LocalizationProvider;
use App\Infrastructure\Test\Faker\Provider\UnitProvider;
use App\Infrastructure\Test\Faker\Provider\UUIDv4;
use Faker\Factory as FakerFactory;

final class Factory extends FakerFactory
{
    public static function create($locale = FakerFactory::DEFAULT_LOCALE): Generator
    {
        $generator = new Generator();

        foreach (self::$defaultProviders as $provider) {
            $providerClassName = self::getProviderClassname($provider, $locale);
            $generator->addProvider(new $providerClassName($generator));
        }


        return $generator;
    }
}
