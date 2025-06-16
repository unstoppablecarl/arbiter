<?php

namespace UnstoppableCarl\Arbiter;

use Illuminate\Contracts\Auth\Authenticatable as AuthUser;
use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;

class TargetSelfOverrides implements TargetSelfOverridesContract
{
    /**
     * Overrides the returned value of an ability when $target is the currently logged-in user.
     * Used to close security holes where users may be able to promote/delete themselves.
     * Ignored when value is null.
     *
     * @return array
     *
     * @example [ 'my_ability' => true, 'my_other_ability' => false, 'fallback_ability' => null]
     * @var array
     */
    protected array $overrides = [];

    public function __construct(array $overrides)
    {
        $this->overrides = $overrides;
    }

    /**
     * Override return values before resolving an ability.
     * @param AuthUser $user
     * @param string $ability
     * @param AuthUser|string $target
     * @return bool|null
     * @see Gate::callPolicyBefore
     */
    public function shouldOverride(AuthUser $user, string $ability, AuthUser|string $target): ?bool
    {
        if (!$this->isSelf($user, $target)) {
            return null;
        }

        $overrides = $this->overrides;

        if (array_key_exists($ability, $overrides)) {
            return $overrides[$ability];
        }

        return null;
    }

    /**
     * Check if $user and $target are the same user
     * @param AuthUser $source
     * @param mixed $target
     * @return bool
     */
    protected function isSelf(AuthUser $source, AuthUser|string $target): bool
    {
        if (!$target instanceof AuthUser) {
            return false;
        }
        return $source->getAuthIdentifier() == $target->getAuthIdentifier();
    }
}
