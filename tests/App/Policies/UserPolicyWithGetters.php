<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Policies;

use UnstoppableCarl\Arbiter\Policies\Concerns\HasGetters;
use UnstoppableCarl\Arbiter\Tests\App\Policies\Concerns\HasTestableUserAuthority;

class UserPolicyWithGetters
{
    use HasTestableUserAuthority;
    use HasGetters;
}
