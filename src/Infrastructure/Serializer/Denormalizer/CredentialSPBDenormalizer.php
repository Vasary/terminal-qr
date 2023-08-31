<?php

declare(strict_types = 1);

namespace App\Infrastructure\Serializer\Denormalizer;

use App\Domain\ValueObject\Credentials\SPB;
use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Host;
use App\Domain\ValueObject\Portal;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

final class CredentialSPBDenormalizer implements DenormalizerInterface
{
    use NormalizerAwareTrait;

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): SPB
    {
        return new SPB(
            new Host($data['host']),
            new Portal($data['portal']),
            new Currency($data['currency']),
        );
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return SPB::class === $type;
    }
}
