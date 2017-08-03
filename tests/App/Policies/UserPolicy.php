<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Policies;

use UnstoppableCarl\Arbiter\Policies\UserPolicy as BaseUserPolicy;

class UserPolicy extends BaseUserPolicy
{
    /**
     * @var array
     */
    protected $overrideWhenSelf = [
        'property_set_true'  => true,
        'property_set_false' => false,
        'customAbilityTrue'  => false,
        'customAbilityFalse' => true,
    ];

    public function customAbilityTrue($user, $target)
    {
        return true;
    }

    public function customAbilityFalse($user, $target)
    {
        return false;
    }
}
