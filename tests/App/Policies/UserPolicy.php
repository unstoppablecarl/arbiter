<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Policies;

use UnstoppableCarl\Arbiter\Policies\UserPolicy as BaseUserPolicy;

class UserPolicy extends BaseUserPolicy
{

    public function customAbilityTrue($user, $target)
    {
        return true;
    }

    public function customAbilityFalse($user, $target)
    {
        return false;
    }
}
