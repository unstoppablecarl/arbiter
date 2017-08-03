<?php

namespace UnstoppableCarl\Arbiter\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface TargetSelfOverridesContract
{
    public function callBefore(array $arguments);

    public function before(Authenticatable $user, $ability, $target);
}
