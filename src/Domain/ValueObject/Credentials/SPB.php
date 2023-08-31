<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\Credentials;

use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Host;
use App\Domain\ValueObject\Portal;

final class SPB extends Credential
{
    public function __construct(private Host $host, private Portal $portal, private Currency $currency,)
    {
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
}
