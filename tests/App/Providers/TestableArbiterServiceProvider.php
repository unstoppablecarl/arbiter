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

    protected $userAuthorityPrimaryRoleAbilities = [];

    protected function userAuthorityPrimaryRoleAbilities()
    {
        return $this->userAuthorityPrimaryRoleAbilities ?: $this->trait_userAuthorityPrimaryRoleAbilities();
    }

    public function set_userAuthorityPrimaryRoleAbilities($userAuthorityPrimaryRoleAbilities)
    {
        $this->userAuthorityPrimaryRoleAbilities = $userAuthorityPrimaryRoleAbilities;
    }

    public function public_registerUserAuthority($shared = false, $concreteClass = UserAuthority::class)
    {
        $this->registerUserAuthority($shared, $concreteClass);
    }

    protected $targetSelfOverrides = [];

    protected function targetSelfOverrides()
    {
        return $this->targetSelfOverrides ?: $this->trait_targetSelfOverrides();
    }

    public function set_targetSelfOverrides($targetSelfOverrides)
    {
        $this->targetSelfOverrides = $targetSelfOverrides;
    }

    public function public_registerTargetSelfOverrides($shared = false, $concreteClass = TargetSelfOverrides::class)
    {
        $this->registerTargetSelfOverrides($shared, $concreteClass);
    }
}