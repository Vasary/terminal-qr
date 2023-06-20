<?php

declare(strict_types = 1);

namespace App\Infrastructure\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final readonly class SecurityUtility
{
    public function __construct(private readonly AuthenticationUtils $authenticationUtils)
    {
    }

    public function getLastAuthenticationError(): ?string
    {
        $response = $this->authenticationUtils->getLastAuthenticationError();

        return $response?->getMessageKey();
    }

    public function getLastUsername(): string
    {
        return $this->authenticationUtils->getLastUsername();
    }
}
