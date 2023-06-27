<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User
{
    private Id $id;
    private DateTimeImmutable $updatedAt;
    private DateTimeImmutable $createdAt;

    private string $password;
    private Collection $stores;

    public function __construct(private Email $email, private array $roles = [])
    {
        $this->id = Id::create();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->password = '';
        $this->stores = new ArrayCollection();
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function withPassword(string $password): void
    {
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function withEmail(string $email): void
    {
        $this->email = new Email($email);
        $this->updatedAt = new DateTimeImmutable();
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function addRole(string $role): void
    {
        $this->roles[] = $role;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function cleanRoles(): void
    {
        $this->roles = [];
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function removeStores(): void
    {
        foreach ($this->stores as $store) {
            /** @var Store $store */
            $this->removeStore($store);
        }
    }

    public function addStore(Store $store): void
    {
        if ($this->stores->contains($store)) {
            return;
        }

        $this->stores->add($store);
        $store->addUser($this);
    }

    public function removeStore(Store $store): void
    {
        if (!$this->stores->contains($store)) {
            return;
        }

        $this->stores->removeElement($store);
        $store->removeUser($this);
    }

    public function stores(): Collection
    {
        return $this->stores;
    }
}
