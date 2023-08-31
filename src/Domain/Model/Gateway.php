<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Credentials\Credential;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Key;
use App\Domain\ValueObject\Title;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Gateway
{
    private Id $id;
    private DateTimeImmutable $updatedAt;
    private readonly DateTimeImmutable $createdAt;
    private Collection $stores;

    public function __construct(
        private Title $title,
        private Callback $callback,
        private readonly Key $key,
        private Credential $credential,
        DateTimeImmutable $now,
    ) {
        $this->id = Id::create();
        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->stores = new ArrayCollection();
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

    public function callback(): Callback
    {
        return $this->callback;
    }

    public function withCallback(string $callback): self
    {
        $this->callback = new Callback($callback);
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    public function withCredentials(Credential $credential): self
    {
        $this->credential = $credential;
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    public function key(): Key
    {
        return $this->key;
    }

    public function addStore(Store $store): void
    {
        $this->stores->add($store);
    }

    public function removeStore(Store $store): void
    {
        if (!$this->stores->contains($store)) {
            return;
        }

        $this->stores->removeElement($store);
        $store->removeGateway($this);
    }

    public function stores(): Collection
    {
        return $this->stores;
    }

    public function credential(): Credential
    {
        return $this->credential;
    }
}
