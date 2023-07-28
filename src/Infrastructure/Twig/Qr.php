<?php

declare(strict_types = 1);

namespace App\Infrastructure\Twig;

use App\Application\Contract\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Domain\Model\QR as Entity;

final class Qr extends AbstractExtension
{
    public function __construct(private readonly TranslatorInterface $translator,)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('qr', [$this, 'qr']),
        ];
    }

    public function qr(?Entity $qr): string
    {
        $message = null !== $qr
            ? $this->translator->trans('payment.attribute.qr.status.registered')
            : $this->translator->trans('payment.attribute.qr.status.absents');

        $class = null !== $qr
            ? 'success'
            : 'dark' ;

        return '<span class="badge text-bg-' . $class . '">' . $message . '</span>';
    }
}
