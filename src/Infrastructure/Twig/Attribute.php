<?php

declare(strict_types = 1);

namespace App\Infrastructure\Twig;

use App\Domain\Model\Payment;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class Attribute extends AbstractExtension
{
    public function __construct(
        private readonly PropertyAccessorInterface $propertyAccessor,
        private readonly Environment $environment,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('paymentItem', [$this, 'paymentItem']),
        ];
    }

    public function paymentItem(Payment $payment, string $accessKey, array $filters = [], array $functions = []): mixed
    {
        $value = $this->propertyAccessor->getValue($payment, $accessKey);

        if (count($functions) > 0) {
            foreach ($functions as $function) {
                $callable = $this->environment->getFunction($function)->getCallable();

                $value = $callable($value);
            }
        }

        if (count($filters) > 0) {
            foreach ($filters as $filter) {
                $callable = $this->environment->getFilter($filter)->getCallable();

                $value = $callable($value);
            }
        }

        return $value;
    }
}
