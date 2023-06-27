<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Host;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Key;
use App\Domain\ValueObject\Portal;
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
        private Host $host,
        private Portal $portal,
        private Currency $currency,
        private Key $key,

    ) {
        $this->id = Id::create();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
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

    public function host(): Host
    {
        return $this->host;
    }

    public function withHost(string $host): self
    {
        $this->host = new Host($host);
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    public function portal(): Portal
    {
        return $this->portal;
    }

    public function withPortal(string $portal): self
    {
        $this->portal = new Portal($portal);
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function withCurrency(string $currency): self
    {
        $this->currency = new Currency($currency);
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
}
