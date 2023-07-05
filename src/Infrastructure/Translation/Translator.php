<?php

declare(strict_types=1);

namespace App\Infrastructure\Translation;

use App\Application\Contract\TranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface as BaseTranslator;

final readonly class Translator implements TranslatorInterface
{
    public function __construct(private BaseTranslator $translator)
    {
    }

    public function trans(string $id): string
    {
        return $this->translator->trans($id);
    }
}
