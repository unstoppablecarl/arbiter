<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Policies;

use UnstoppableCarl\Arbiter\Policies\UserPolicy;

class TestableUserPolicy extends UserPolicy
{
    public function getUserAuthority(){
        return $this->userAuthority();
    }

    public function getTargetSelfOverrides(){
        return $this->targetSelfOverrides;
    }
}