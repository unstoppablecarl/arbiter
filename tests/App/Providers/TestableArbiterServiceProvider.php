<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Providers;

use Illuminate\Support\ServiceProvider;
use UnstoppableCarl\Arbiter\Providers\Concerns\HandlesArbiterBindings;
use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\UserAuthority;

class TestableArbiterServiceProvider extends ServiceProvider
{
    use HandlesArbiterBindings;

    protected $userAuthorityPrimaryRoleAbilities = [];
    protected $targetSelfOverrides               = [];

    public function public_registerUserAuthority($shared = false)
    {
        $this->registerUserAuthority($shared);
    }

    public function public_registerTargetSelfOverrides($shared = false)
    {
        $this->registerTargetSelfOverrides($shared);
    }

    public function setTargetSelfOverrides($targetSelfOverrides)
    {
        $this->targetSelfOverrides = $targetSelfOverrides;
    }

    public function setUserAuthorityPrimaryRoleAbilities($userAuthorityPrimaryRoleAbilities)
    {
        $this->userAuthorityPrimaryRoleAbilities = $userAuthorityPrimaryRoleAbilities;
    }

    protected function userAuthorityPrimaryRoleAbilities()
    {
        return $this->userAuthorityPrimaryRoleAbilities;
    }

    protected function targetSelfOverrides()
    {
        return $this->targetSelfOverrides;
    }
}