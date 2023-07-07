<?php

declare(strict_types = 1);

namespace App\Infrastructure\Twig;

use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\Model\Payment;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class PaymentStatus extends AbstractExtension
{
    private const statusMap = [
        PaymentStatusEnum::Init->value => 'dark',
        PaymentStatusEnum::Token->value => 'info',
        PaymentStatusEnum::Registered->value => 'secondary',
        PaymentStatusEnum::Awaiting->value => 'warning',
        PaymentStatusEnum::Successfully->value => 'success',
        PaymentStatusEnum::Failure->value => 'danger',
        PaymentStatusEnum::Timeout->value => 'danger',
        PaymentStatusEnum::Unknown->value => 'danger',
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

    public function paymentStatus(Payment $payment): string
    {
        $statusClass = self::statusMap[$payment->status()->value];

        return $this->getTemplate($statusClass, sprintf('payment.status.%s', strtolower($payment->status()->name)));
    }

    private function getTemplate(string $bootstrapClass, string $value): string
    {
        return '<span class="badge rounded-pill bg-' . $bootstrapClass . '">' . $this->translator->trans(
            $value
        ) . '</span>';
    }
}
