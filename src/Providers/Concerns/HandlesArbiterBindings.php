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
            return $this->buildUserAuthority(
                $this->userAuthorityPrimaryRoleAbilities()
            );
        }, $shared);
    }

    protected function buildUserAuthority($primaryRoleAbilities)
    {
        return new UserAuthority($primaryRoleAbilities);
    }

    protected function userAuthorityPrimaryRoleAbilities()
    {
        return [];
    }

    protected function registerTargetSelfOverrides($shared = false)
    {
        $this->app->bind(TargetSelfOverridesContract::class, function () {
            return $this->buildTargetSelfOverrides(
                $this->targetSelfOverrides()
            );
        }, $shared);
    }

    protected function buildTargetSelfOverrides(array $overrides)
    {
        return new TargetSelfOverrides($overrides);
    }

    protected function targetSelfOverrides()
    {
        return [];
    }
}
