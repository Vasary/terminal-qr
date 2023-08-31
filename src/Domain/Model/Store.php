<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\Description;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Title;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Store
{
    private Id $id;
    private DateTimeImmutable $updatedAt;
    private readonly DateTimeImmutable $createdAt;

    /** @var Collection<Gateway> */
    private Collection $gateways;

    private Collection $users;

    public function __construct(
        private Title $title,
        private readonly Code $code,
        private Description $description,
        DateTimeImmutable $now,
    ) {
        $this->id = Id::create();
        $this->gateways = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function withTitle(string $title): self
    {
        $this->title = new Title($title);
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    public function code(): Code
    {
        return $this->code;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function withDescription(string $description): self
    {
        $this->description = new Description($description);
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    public function addGateway(Gateway $gateway): void
    {
        $this->gateways->add($gateway);
        $gateway->addStore($this);
    }

    public function removeGateway(Gateway $gateway): void
    {
        if (!$this->gateways->contains($gateway)) {
            return;
        }

        $this->gateways->removeElement($gateway);
        $gateway->removeStore($this);
    }

    public function gateway(): Collection
    {
        return $this->gateways;
    }

    public function addUser(User $user): void
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->users->add($user);
        $user->addStore($this);
    }

    public function removeUser(User $user): void
    {
        if (!$this->users->contains($user)) {
            return;
        }

        $this->users->removeElement($user);
        $user->removeStore($this);
    }

    public function users(): Collection
    {
        return $this->users;
    }
}
