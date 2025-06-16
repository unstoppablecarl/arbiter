<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Policies;

use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Policies\UserPolicy;

class TestableUserPolicy extends UserPolicy
{
    public function getUserAuthority(): UserAuthorityContract
    {
        return $this->userAuthority();
    }

    public function getTargetSelfOverrides(): TargetSelfOverridesContract
    {
        return $this->targetSelfOverrides;
    }
}
