<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\Tests\App\Policies\TestableUserPolicy;
use UnstoppableCarl\Arbiter\Tests\TestCase;

#[CoversClass(\UnstoppableCarl\Arbiter\Policies\UserPolicy::class)]
class UserPolicyTest extends TestCase
{

    public function testUserPolicyConstructor()
    {
        $userAuthority = $this->createMock(UserAuthorityContract::class);
        $targetSelfOverrides = $this->createMock(TargetSelfOverrides::class);

        $policy = new TestableUserPolicy($userAuthority, $targetSelfOverrides);

        $this->assertSame($userAuthority, $policy->getUserAuthority());
        $this->assertSame($targetSelfOverrides, $policy->getTargetSelfOverrides());
    }
}
