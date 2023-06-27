<?php

declare(strict_types = 1);

namespace App\Tests\Application\User;

use App\Application\User\User;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Infrastructure\Test\Context\Model\UserContext;

final class UserTest extends AbstractUnitTestCase
{
    public function testApplicationUserProvideAccessToDomainUser(): void
    {
        $user = UserContext::create()();

        $applicationUser = new User($user);

        $applicationUser->eraseCredentials();

        $this->assertEquals($user->roles(), $applicationUser->getRoles());
        $this->assertEquals((string) $user->id(), $applicationUser->getUserIdentifier());
        $this->assertEquals($user->password(), $applicationUser->getPassword());
        $this->assertEquals($user, $applicationUser->getDomainUser());
    }
}
