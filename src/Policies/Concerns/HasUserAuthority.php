<?php


namespace UnstoppableCarl\Arbiter\Policies\Concerns;

use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole as User;

trait HasUserAuthority
{
    protected UserAuthorityContract $userAuthority;

    /**
     * Normalize potential User value to primary role name.
     * @param User|string|null $value primary role name or User to get primary role from
     * @return string|null
     */
    protected function toPrimaryRole(User|string|null $value): ?string
    {
        if ($value instanceof User) {
            return $value->getPrimaryRoleName();
        }
        return $value;
    }

    protected function userAuthority(): UserAuthorityContract
    {
        return $this->userAuthority;
    }
}
