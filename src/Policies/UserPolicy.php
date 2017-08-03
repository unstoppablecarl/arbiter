<?php

namespace UnstoppableCarl\Arbiter\Policies;

use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Policies\Concerns\HasAbilities;
use UnstoppableCarl\Arbiter\Policies\Concerns\HasGetters;
use UnstoppableCarl\Arbiter\Policies\Concerns\HasTargetSelfOverrides;
use UnstoppableCarl\Arbiter\Policies\Concerns\HasUserAuthority;

class UserPolicy
{
    use HasUserAuthority;
    use HasTargetSelfOverrides;
    use HasAbilities;
    use HasGetters;

    /**
     * UserPolicy constructor.
     * @param UserAuthorityContract $userAuthority
     * @param TargetSelfOverridesContract|null $targetSelfOverrides
     */
    public function __construct(
        UserAuthorityContract $userAuthority,
        TargetSelfOverridesContract $targetSelfOverrides
    ) {
        $this->userAuthority       = $userAuthority;
        $this->targetSelfOverrides = $targetSelfOverrides;
    }
}
