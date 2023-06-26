<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\User\Form;

use Symfony\Component\Validator\Constraints as Assert;

final class Data
{
    #[Assert\Email]
    #[Assert\NotBlank]
    public string $email;

    #[Assert\Length(min: 0, max: 25)]
    public string $password;

    #[Assert\NotBlank]
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
