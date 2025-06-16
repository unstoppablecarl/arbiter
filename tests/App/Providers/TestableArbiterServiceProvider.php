<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Providers;

use Illuminate\Support\ServiceProvider;
use UnstoppableCarl\Arbiter\Providers\Concerns\HandlesArbiterBindings;
use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\UserAuthority;

class TestableArbiterServiceProvider extends ServiceProvider
{
    use HandlesArbiterBindings {
        targetSelfOverrides as trait_targetSelfOverrides;
        userAuthorityPrimaryRoleAbilities as trait_userAuthorityPrimaryRoleAbilities;
    }

    protected array $userAuthorityPrimaryRoleAbilities = [];

    protected function userAuthorityPrimaryRoleAbilities()
    {
        return $this->userAuthorityPrimaryRoleAbilities ?: $this->trait_userAuthorityPrimaryRoleAbilities();
    }

    public function setUserAuthorityPrimaryRoleAbilities($userAuthorityPrimaryRoleAbilities): void
    {
        $this->userAuthorityPrimaryRoleAbilities = $userAuthorityPrimaryRoleAbilities;
    }

    public function publicRegisterUserAuthority($shared = false, $concreteClass = UserAuthority::class): void
    {
        $this->registerUserAuthority($shared, $concreteClass);
    }

    protected array $targetSelfOverrides = [];

    protected function targetSelfOverrides(): array
    {
        return $this->targetSelfOverrides ?: $this->trait_targetSelfOverrides();
    }

    public function setTargetSelfOverrides($targetSelfOverrides): void
    {
        $this->targetSelfOverrides = $targetSelfOverrides;
    }

    public function publicRegisterTargetSelfOverrides($shared = false, $concreteClass = TargetSelfOverrides::class): void
    {
        $this->registerTargetSelfOverrides($shared, $concreteClass);
    }
}
