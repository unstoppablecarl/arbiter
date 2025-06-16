<?php


namespace UnstoppableCarl\Arbiter\Policies\Concerns;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthUser;
use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;

trait HasTargetSelfOverrides
{
    protected TargetSelfOverridesContract $targetSelfOverrides;

    public function before(Authenticatable $user, string $ability, AuthUser|string $target)
    {
        return call_user_func_array([$this->targetSelfOverrides, 'shouldOverride'], func_get_args());
    }
}
