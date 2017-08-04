<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Policies\Concerns;

use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Policies\Concerns\HasUserAuthority;

trait HasTestableUserAuthority
{
    use HasUserAuthority;

    public function __construct(UserAuthorityContract $userAuthority)
    {
        $this->userAuthority = $userAuthority;
    }
}