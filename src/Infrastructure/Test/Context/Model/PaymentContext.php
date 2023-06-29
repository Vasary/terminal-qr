<?php

declare(strict_types=1);

namespace App\Infrastructure\Test\Context\Model;

use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\Model\Payment;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Test\Context\ContextTrait;
use App\Infrastructure\Test\Context\ModelContextInterface;
use App\Infrastructure\Test\Context\ModelContextTrait;
use App\Infrastructure\Test\Context\StandaloneTrait;
use App\Infrastructure\Test\Context\TimestampTrait;

final class PaymentContext implements ModelContextInterface
{
    use ContextTrait;
    use ModelContextTrait;
    use StandaloneTrait;
    use TimestampTrait;

    public string $id = 'abba44b6-e3e5-477c-a3a1-b78dce149d4d';
    public int $amount = 100;
    public int $commission = 0;
    public PaymentStatusEnum $status = PaymentStatusEnum::Init;
    public string $callbackUrl = 'http://localhost/callback';
    public string $idGateway = 'e96f2520-2ce2-488e-879b-d11f3796f7cd';
    public string $idStore = '2cdb88ac-71af-4b3d-b8df-51fbbca74a6b';

    public string $currency = 'RUB';

    public function __invoke(bool $singleton = true): Payment
    {
        /** @var Payment $model */
        $model = $this->getInstance(Payment::class);

        $this
            ->setProperty($model, 'id', Id::fromString($this->id))
            ->setProperty($model, 'amount', $this->amount)
            ->setProperty($model, 'commission', $this->commission)
            ->setProperty($model, 'status', $this->status)
            ->setProperty($model, 'currency', $this->currency)
            ->setProperty($model, 'callbackUrl', $this->callbackUrl)
            ->setProperty($model, 'gateway', Id::fromString($this->idGateway))
            ->setProperty($model, 'store', Id::fromString($this->idStore))
            ->setProperty($model, 'logs', [])
            ->setTimestamps($model);

        return $singleton ? $this->obtainInstance($model) : $model;
    }
}
