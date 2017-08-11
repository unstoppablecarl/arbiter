<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Policies;

use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Policies\Concerns\HasUserAuthority;

class UserPolicyWithUserAuthority
{
    use HasUserAuthority {
        toPrimaryRole as trait_toPrimaryRole;
        userAuthority as trait_userAuthority;
    }

    public function setUserAuthority(UserAuthorityContract $userAuthority)
    {
        $this->userAuthority = $userAuthority;
    }

    public function toPrimaryRole($value)
    {
        return $this->trait_toPrimaryRole($value);
    }

    public function userAuthority()
    {
        return $this->trait_userAuthority();
    }
}
