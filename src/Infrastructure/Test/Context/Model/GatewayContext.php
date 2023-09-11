<?php

declare(strict_types=1);

namespace App\Infrastructure\Test\Context\Model;

use App\Domain\Model\Gateway;
use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Credentials\Credential;
use App\Domain\ValueObject\Credentials\Stub;
use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Key;
use App\Domain\ValueObject\Title;
use App\Infrastructure\Test\Context\ContextTrait;
use App\Infrastructure\Test\Context\ModelContextInterface;
use App\Infrastructure\Test\Context\ModelContextTrait;
use App\Infrastructure\Test\Context\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @method self withId(Id $id)
 * @method self withTitle(Title $title)
 * @method self withCallback(Callback $callback)
 * @method self withKey(Key $key)
 * @method self withCurrency(Currency $currency)
 * @method self withCredential(Credential $credential)
 */
final class GatewayContext implements ModelContextInterface
{
    use ContextTrait;
    use ModelContextTrait;
    use TimestampTrait;

    private Id $id;
    private Title $title;
    private Callback $callback;
    private Key $key;
    private Currency $currency;
    private Credential $credential;

    public function __construct()
    {
        $this->id = Id::fromString('3091c5e9-7886-469a-a15e-18a4d9f11134');
        $this->title = new Title('My gateway');
        $this->callback = new Callback('http://localhost/callback');
        $this->key = new Key('xxx');
        $this->currency = new Currency('USD');
        $this->credential = new Stub('login', 'password');
    }

    public function __invoke(): Gateway
    {
        /** @var Gateway $model */
        $model = $this->getInstance(Gateway::class);

        $this
            ->setProperty($model, 'id', $this->id)
            ->setProperty($model, 'title', $this->title)
            ->setProperty($model, 'callback', $this->callback)
            ->setProperty($model, 'key', $this->key)
            ->setProperty($model, 'credential', $this->credential)
            ->setProperty($model, 'currency', $this->currency)
            ->setProperty($model, 'stores', new ArrayCollection())
            ->setTimestamps($model);

        return $model;
    }
}
