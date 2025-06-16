<?php

namespace UnstoppableCarl\Arbiter\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthUser;

interface TargetSelfOverridesContract
{
    public function shouldOverride(Authenticatable $user, string $ability, AuthUser|string $target);
}
