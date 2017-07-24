<?php

namespace UnstoppableCarl\Arbiter\Policies\Concerns;

use Illuminate\Contracts\Auth\Authenticatable as AuthUser;

trait OverrideWhenSelf
{
    /**
     * Overrides the returned value of an ability when $target is the currently logged in user.
     * Used to close security holes where users may be able to promote/delete themselves.
     * Ignored when value is null.
     *
     * @var array
     * @example [ 'my_ability' => true, 'my_other_ability' => false]
     * @return array
     *
     * @codeCoverageIgnore
     */
    protected function overrideWhenSelf()
    {
        if (property_exists($this, 'overrideWhenSelf')) {
            return $this->overrideWhenSelf;
        }

        return [];
    }

    /**
     * Override return values before resolving an ability.
     * @see Gate::callPolicyBefore
     * @param AuthUser $user
     * @param string $ability
     * @param User|string $target
     * @return bool|null
     */
    public function before(AuthUser $user, $ability, $target)
    {
        if (!$this->isOverridableAbility($ability)) {
            return null;
        }

        if (!$this->isSelf($user, $target)) {
            return null;
        }

        $overrides = $this->overrideWhenSelf();

        if (array_key_exists($ability, $overrides)) {
            return $overrides[$ability];
        }

        return null;
    }

    /**
     * Determin if an ability can be overriden.
     * If you want to allow all abilities,
     * replace this method with one that always returns true
     * @param string $ability
     * @return bool
     */
    protected function isOverridableAbility($ability)
    {
        return method_exists($this, $ability);
    }

    /**
     * Check if $user and $target are the same user
     * @param AuthUser $source
     * @param mixed $target
     * @return bool
     */
    protected function isSelf(AuthUser $source, $target)
    {
        if (!$target instanceof AuthUser) {
            return false;
        }
        return $source->getAuthIdentifier() == $target->getAuthIdentifier();
    }
}
