<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\User\Form;

use App\Infrastructure\Assert\Email;
use App\Infrastructure\Assert\Length;
use App\Infrastructure\Assert\NotBlank;
use App\Infrastructure\Form\Data\PropertyAccessorTrait;

final class Data
{
    use PropertyAccessorTrait;

    #[Email]
    #[NotBlank]
    private string $email;

    #[Length(min: 0, max: 25)]
    private string $password;

    #[NotBlank]
    private string $roles;

    /**
     * @var string[]
     */
    private array $stores;

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'roles' => [$this->roles],
            'stores' => $this->stores,
        ];
    }

    public function email(): string
    {
        return $this->email;
    }

    public function withEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function withPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function roles(): string
    {
        return $this->roles;
    }

    public function withRoles(string $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function stores(): array
    {
        return $this->stores;
    }

    public function withStores(array $stores): self
    {
        $this->stores = $stores;
        return $this;
    }

    public function addStore(string $store): self
    {
        $this->stores[] = $store;
        return $this;
    }
}
