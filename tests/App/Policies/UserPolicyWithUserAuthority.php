<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Policies;

use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole as User;
use UnstoppableCarl\Arbiter\Policies\Concerns\HasUserAuthority;

class UserPolicyWithUserAuthority
{
    use HasUserAuthority {
        toPrimaryRole as trait_toPrimaryRole;
        userAuthority as trait_userAuthority;
    }

    public function setUserAuthority(UserAuthorityContract $userAuthority): void
    {
        $this->userAuthority = $userAuthority;
    }

    public function toPrimaryRole(User|string|null $value): ?string
    {
        return $this->trait_toPrimaryRole($value);
    }

    public function userAuthority(): UserAuthorityContract
    {
        return $this->trait_userAuthority();
    }
}
