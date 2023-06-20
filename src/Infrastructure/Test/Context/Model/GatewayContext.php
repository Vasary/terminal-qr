<?php

declare(strict_types=1);

namespace App\Infrastructure\Test\Context\Model;

use App\Domain\Model\Gateway;
use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Host;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Key;
use App\Domain\ValueObject\Portal;
use App\Infrastructure\Test\Context\ContextTrait;
use App\Infrastructure\Test\Context\ModelContextInterface;
use App\Infrastructure\Test\Context\ModelContextTrait;
use App\Infrastructure\Test\Context\StandaloneTrait;
use App\Infrastructure\Test\Context\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;

final class GatewayContext implements ModelContextInterface
{
    use ContextTrait;
    use ModelContextTrait;
    use StandaloneTrait;
    use TimestampTrait;

    public string $id = '3091c5e9-7886-469a-a15e-18a4d9f11134';
    public string $callback = 'http://localhost/callback';
    public string $host = 'localhost';
    public string $portal = 'portal';
    public string $currency = 'RUB';
    public string $key = 'xxx';

    public function __invoke(bool $singleton = true): Gateway
    {
        /** @var Gateway $model */
        $model = $this->getInstance(Gateway::class);

        $this
            ->setProperty($model, 'id', Id::fromString($this->id))
            ->setProperty($model, 'callback', new Callback($this->callback))
            ->setProperty($model, 'host', new Host($this->host))
            ->setProperty($model, 'portal', new Portal($this->portal))
            ->setProperty($model, 'currency', new Currency($this->currency))
            ->setProperty($model, 'key', new Key($this->key))
            ->setProperty($model, 'stores', new ArrayCollection())
            ->setTimestamps($model);

        return $singleton ? $this->obtainInstance($model) : $model;
    }
}
