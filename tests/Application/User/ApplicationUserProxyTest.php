<?php

declare(strict_types = 1);

use App\Application\User\User;
use App\Infrastructure\Test\Context\Model\UserContext;

it('provides access to domain user via application user proxy', function () {
    $user = UserContext::create()();

    $applicationUser = new User($user);

    $applicationUser->eraseCredentials();

    expect($applicationUser->getRoles())->toBe($user->roles())
        ->and($applicationUser->getUserIdentifier())->toEqual((string) $user->id())
        ->and($applicationUser->getPassword())->toBe($user->password())
        ->and($applicationUser->getDomainUser())->toBe($user);
});
