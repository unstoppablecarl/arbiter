<?php

namespace UnstoppableCarl\Arbiter\Providers\Concerns;

use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\UserAuthority;

trait HandlesArbiterBindings
{
    protected function registerUserAuthority($shared = false)
    {
        $this->app->bind(UserAuthorityContract::class, function () {
            return new UserAuthority(
                $this->userAuthorityPrimaryRoleAbilities()
            );
        }, $shared);
    }

    protected function registerTargetSelfOverrides($shared = false)
    {
        $this->app->bind(TargetSelfOverridesContract::class, function () {
            return new TargetSelfOverrides(
                $this->targetSelfOverrides()
            );
        }, $shared);
    }

    protected function userAuthorityPrimaryRoleAbilities()
    {
        return [];
    }

    protected function targetSelfOverrides()
    {
        return [];
    }
}
