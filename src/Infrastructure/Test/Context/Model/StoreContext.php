<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Context\Model;

use App\Domain\Model\Store;
use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\Description;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Title;
use App\Infrastructure\Test\Context\ContextTrait;
use App\Infrastructure\Test\Context\ModelContextInterface;
use App\Infrastructure\Test\Context\ModelContextTrait;
use App\Infrastructure\Test\Context\StandaloneTrait;
use App\Infrastructure\Test\Context\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;

final class StoreContext implements ModelContextInterface
{
    use ContextTrait;
    use ModelContextTrait;
    use StandaloneTrait;
    use TimestampTrait;

    public string $id = '888c23c6-06fe-4a95-a66c-f292da2f7607';
    public string $title = 'My store';
    public string $code = 'coffee';
    public string $description = 'My test description';

    public function __invoke(bool $singleton = true): Store
    {
        /** @var Store $model */
        $model = $this->getInstance(Store::class);

        $this
            ->setProperty($model, 'id', Id::fromString($this->id))
            ->setProperty($model, 'title', new Title($this->title))
            ->setProperty($model, 'code', new Code($this->code))
            ->setProperty($model, 'description', new Description($this->description))
            ->setProperty($model, 'gateways', new ArrayCollection())
            ->setTimestamps($model);

        return $singleton ? $this->obtainInstance($model) : $model;
    }
}
