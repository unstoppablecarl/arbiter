<?php


namespace UnstoppableCarl\Arbiter\Policies\Concerns;

use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole as User;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;

trait HasUserAuthority
{
    /**
     * @var UserAuthorityContract
     */
    protected $userAuthority;

    /**
     * Normalize potential User value to primary role name.
     * @param string|User $value primary role name or User to get primary role from
     * @return string
     */
    protected function toPrimaryRole($value)
    {
        if ($value instanceof User) {
            return $value->getPrimaryRoleName();
        }
        return $value;
    }

    /**
     * @return UserAuthorityContract
     */
    protected function userAuthority()
    {
        return $this->userAuthority;
    }
}
