<?php

namespace UnstoppableCarl\Arbiter\Providers\Concerns;

use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\UserAuthority;

trait HandlesArbiterBindings
{
    /**
     * @param bool $shared
     * @param string $concreteClass Used for testing
     */
    protected function registerUserAuthority($shared = false, $concreteClass = UserAuthority::class)
    {
        $this->app->bind(UserAuthorityContract::class, function () use ($concreteClass) {
            return new $concreteClass(
                $this->userAuthorityPrimaryRoleAbilities()
            );
        }, $shared);
    }

    /**
     * @return array
     */
    protected function userAuthorityPrimaryRoleAbilities()
    {
        return [];
    }

    /**
     * @param bool $shared
     * @param string $concreteClass Used for testing
     */
    protected function registerTargetSelfOverrides($shared = false, $concreteClass = TargetSelfOverrides::class)
    {
        $this->app->bind(TargetSelfOverridesContract::class, function () use ($concreteClass) {
            return new $concreteClass(
                $this->targetSelfOverrides()
            );
        }, $shared);
    }

    /**
     * @return array
     */
    protected function targetSelfOverrides()
    {
        return [];
    }
}
