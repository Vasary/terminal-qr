<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\User\Form;

use App\Infrastructure\Assert\Email;
use App\Infrastructure\Assert\Length;
use App\Infrastructure\Assert\NotBlank;

final class Data
{
    #[Email]
    #[NotBlank]
    public string $email;

    #[Length(min: 0, max: 25)]
    public string $password;

    #[NotBlank]
    public string $roles;

    public array $stores;

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'roles' => [$this->roles],
            'stores' => $this->stores,
        ];
    }
}
