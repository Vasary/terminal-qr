<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Context\Model;

use App\Domain\Model\User;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Test\Context\ContextTrait;
use App\Infrastructure\Test\Context\ModelContextInterface;
use App\Infrastructure\Test\Context\ModelContextTrait;
use App\Infrastructure\Test\Context\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;

final class UserContext implements ModelContextInterface
{
    use ContextTrait;
    use ModelContextTrait;
    use TimestampTrait;

    public string $id = '6b58caa4-0571-44db-988a-8a75f86b2520';
    public string $email = 'test@localhost.com';

    public function __invoke(): User
    {
        /** @var User $model */
        $model = $this->getInstance(User::class);

        $this
            ->setProperty($model, 'id', Id::fromString($this->id))
            ->setProperty($model, 'password', '')
            ->setProperty($model, 'email', new Email($this->email))
            ->setProperty($model, 'roles', ['ROLE_ADMIN', 'ROLE_MANAGER'])
            ->setProperty($model, 'stores', new ArrayCollection())
            ->setTimestamps($model);

        return $model;
    }
}
