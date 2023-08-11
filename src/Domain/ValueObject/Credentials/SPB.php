<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\Credentials;

use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Host;
use App\Domain\ValueObject\Key;
use App\Domain\ValueObject\Portal;
use App\Domain\ValueObject\Title;

final class SPB extends Credential
{
    public function __construct(
        private Title $title,
        private Callback $callback,
        private Host $host,
        private Portal $portal,
        private Currency $currency,
        private readonly Key $key,
    )
    {
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function withTitle(string $title): self
    {
        $this->title = new Title($title);

        return $this;
    }

    public function callback(): Callback
    {
        return $this->callback;
    }

    public function withCallback(string $callback): self
    {
        $this->callback = new Callback($callback);

        return $this;
    }

    public function host(): Host
    {
        return $this->host;
    }

    public function withHost(string $host): self
    {
        $this->host = new Host($host);

        return $this;
    }

    public function portal(): Portal
    {
        return $this->portal;
    }

    public function withPortal(string $portal): self
    {
        $this->portal = new Portal($portal);

        return $this;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function withCurrency(string $currency): self
    {
        $this->currency = new Currency($currency);

        return $this;
    }

    public function key(): Key
    {
        return $this->key;
    }
}
