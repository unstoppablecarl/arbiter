<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Policies;

use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Policies\Concerns\HasTargetSelfOverrides;

class UserPolicyWithTargetSelfOverrides
{
    use HasTargetSelfOverrides;

    public function setTargetSelfOverrides(TargetSelfOverridesContract $overrides)
    {
        $this->targetSelfOverrides = $overrides;
    }
}
