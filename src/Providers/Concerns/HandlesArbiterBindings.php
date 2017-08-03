<?php

namespace UnstoppableCarl\Arbiter\Providers\Concerns;

use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\UserAuthority;

trait HandlesArbiterBindings
{
    protected function registerUserAuthoritySingleton($concreteClass = UserAuthority::class)
    {
        $this->registerUserAuthority($concreteClass, true);
    }

    protected function registerUserAuthority($concreteClass = UserAuthority::class, $shared = false)
    {
        $this->app->bind(UserAuthorityContract::class, function () use ($concreteClass) {
            return new $concreteClass(
                $this->userAuthorityAbilities()
            );
        }, $shared);
    }

    protected function registerTargetSelfOverridesSingleton($concreteClass = TargetSelfOverrides::class)
    {
        $this->registerUserAuthority($concreteClass, true);
    }

    protected function registerTargetSelfOverrides($concreteClass = TargetSelfOverrides::class, $shared = false)
    {
        $this->app->bind(TargetSelfOverridesContract::class, function () use ($concreteClass) {
            return new $concreteClass(
                $this->userAuthorityPrimaryRoleAbilities()
            );
        }, $shared);
    }

    protected function userAuthorityAbilities()
    {
        return [];
    }

    protected function userAuthorityPrimaryRoleAbilities()
    {
        return [];
    }
}
