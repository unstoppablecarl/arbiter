<?php

namespace UnstoppableCarl\Arbiter;

use Illuminate\Contracts\Auth\Authenticatable as AuthUser;
use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;

class TargetSelfOverrides implements TargetSelfOverridesContract
{
    /**
     * Overrides the returned value of an ability when $target is the currently logged in user.
     * Used to close security holes where users may be able to promote/delete themselves.
     * Ignored when value is null.
     *
     * @var array
     * @example [ 'my_ability' => true, 'my_other_ability' => false, 'fallback_ability' => null]
     * @return array
     *
     * @codeCoverageIgnore
     */
    protected $overrides = [];

    /**
     * TargetSelfAbilityOverrides constructor.
     * @param array $overrides
     */
    public function __construct(array $overrides)
    {
        $this->overrides = $overrides;
    }

    /**
     * Call $this->before using array of arguments
     * Simplifies calling with func_get_args()
     * @param array $arguments
     * @return bool|null
     */
    public function callBefore(array $arguments)
    {
        return call_user_func([$this, 'before'], $arguments);
    }

    /**
     * Override return values before resolving an ability.
     * @see Gate::callPolicyBefore
     * @param AuthUser $user
     * @param string $ability
     * @param AuthUser|string $target
     * @return bool|null
     */
    public function before(AuthUser $user, $ability, $target)
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
    protected function isSelf(AuthUser $source, $target)
    {
        if (!$target instanceof AuthUser) {
            return false;
        }
        return $source->getAuthIdentifier() == $target->getAuthIdentifier();
    }
}
