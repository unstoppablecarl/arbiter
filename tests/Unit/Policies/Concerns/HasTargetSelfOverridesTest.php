<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit\Policies\Concerns;

use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Tests\App\Policies\UserPolicyWithTargetSelfOverrides;
use UnstoppableCarl\Arbiter\Tests\TestCase;

/**
 * @covers \UnstoppableCarl\Arbiter\Policies\Concerns\HasTargetSelfOverrides
 */
class HasTargetSelfOverridesTest extends TestCase
{

    public function testBefore()
    {
        $overrides = $this->createMock(TargetSelfOverridesContract::class);

        $policy = new UserPolicyWithTargetSelfOverrides();

        $user    = 'user';
        $ability = 'ability';
        $target  = 'target';
        $extra1  = 'extra1';
        $extra2  = 'extra2';

        $overrides
            ->expects($this->once())
            ->method('callBefore')
            ->with([$user, $ability, $target, $extra1, $extra2]);

        $policy->setTargetSelfOverrides($overrides);

        $policy->before($user, $ability, $target, $extra1, $extra2);
    }
}
