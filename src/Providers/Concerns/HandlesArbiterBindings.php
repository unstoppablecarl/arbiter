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
    protected function registerUserAuthority(bool $shared = false, string $concreteClass = UserAuthority::class): void
    {
        $this->app->bind(UserAuthorityContract::class, function () use ($concreteClass) {
            return new $concreteClass(
                $this->userAuthorityPrimaryRoleAbilities()
            );
        }, $shared);
    }

    protected function userAuthorityPrimaryRoleAbilities(): array
    {
        return [];
    }

    protected function registerTargetSelfOverrides(bool $shared = false, string $concreteClass = TargetSelfOverrides::class): void
    {
        $this->app->bind(TargetSelfOverridesContract::class, function () use ($concreteClass) {
            return new $concreteClass(
                $this->targetSelfOverrides()
            );
        }, $shared);
    }

    protected function targetSelfOverrides(): array
    {
        return [];
    }
}
