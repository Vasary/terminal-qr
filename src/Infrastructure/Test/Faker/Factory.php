<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Faker;

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
