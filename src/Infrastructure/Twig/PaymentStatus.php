<?php

declare(strict_types = 1);

namespace App\Infrastructure\Twig;

use App\Domain\Enum\PaymentStatusEnum;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class PaymentStatus extends AbstractExtension
{
    private const statusMap = [
        PaymentStatusEnum::new->value => 'primary',
        PaymentStatusEnum::awaiting->value => 'warning',
        PaymentStatusEnum::successfully->value => 'success',
        PaymentStatusEnum::failure->value => 'danger',
    ];

    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('paymentStatus', [$this, 'paymentStatus']),
        ];
    }

    public function paymentStatus(PaymentStatusEnum $status): string
    {
        $statusClass = self::statusMap[$status->value];

        return $this->getTemplate($statusClass, sprintf('payment.status.%s', strtolower($status->name)));
    }

    private function getTemplate(string $bootstrapClass, string $value): string
    {
        return '<span class="badge rounded-pill bg-' . $bootstrapClass . '">' . $this->translator->trans(
            $value
        ) . '</span>';
    }
}
