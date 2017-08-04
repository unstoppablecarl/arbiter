<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit;

use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Tests\App\Policies\TestableUserPolicy;
use UnstoppableCarl\Arbiter\Tests\TestCase;

/**
 * @covers \UnstoppableCarl\Arbiter\Policies\UserPolicy::__construct()
 */
class UserPolicyTest extends TestCase
{

    public function testUserPolicyConstructor()
    {
        $userAuthority       = $this->createMock(UserAuthorityContract::class);
        $targetSelfOverrides = $this->createMock(TargetSelfOverridesContract::class);

        $policy = new TestableUserPolicy($userAuthority, $targetSelfOverrides);

        $this->assertSame($userAuthority, $policy->getUserAuthority());
        $this->assertSame($targetSelfOverrides, $policy->getTargetSelfOverrides());
    }
}
