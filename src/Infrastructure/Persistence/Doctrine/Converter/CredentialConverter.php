<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Converter;

use App\Domain\ValueObject\Credentials\Credential;
use App\Infrastructure\Serializer\Serializer;
use Doctrine\DBAL\Types\ConversionException;
use JsonException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

final class CredentialConverter
{
    public static function toObject(string $value): Credential
    {
        $data = json_decode($value, true);

        return Serializer::create()->toType($data, $data['type']);
    }

    /**
     * @throws ExceptionInterface
     * @throws ConversionException
     */
    public static function toString(Credential $value): string
    {
        $value = array_merge(Serializer::create()->toArray($value), ['type' => $value::class]);

        try {
            return json_encode($value, JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);
        } catch (JsonException $e) {
            throw ConversionException::conversionFailedSerialization($value, 'json', $e->getMessage(), $e);
        }
    }
}
