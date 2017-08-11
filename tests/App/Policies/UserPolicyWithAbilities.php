<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Policies;

use UnstoppableCarl\Arbiter\Policies\Concerns\HasAbilities;
use UnstoppableCarl\Arbiter\Tests\App\Policies\Concerns\HasTestableUserAuthority;

class UserPolicyWithAbilities
{
    use HasTestableUserAuthority;
    use HasAbilities;
}
