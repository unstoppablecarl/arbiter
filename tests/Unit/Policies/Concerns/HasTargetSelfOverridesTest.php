<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit\Policies\Concerns;

use Illuminate\Contracts\Auth\Authenticatable;
use PHPUnit\Framework\Attributes\CoversTrait;
use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Tests\App\Policies\UserPolicyWithTargetSelfOverrides;
use UnstoppableCarl\Arbiter\Tests\TestCase;

#[CoversTrait(\UnstoppableCarl\Arbiter\Policies\Concerns\HasTargetSelfOverrides::class)]
class HasTargetSelfOverridesTest extends TestCase
{
    public function testBefore()
    {
        $overrides = $this->createMock(TargetSelfOverridesContract::class);

        $policy = new UserPolicyWithTargetSelfOverrides();

        $user = $this->createMock(Authenticatable::class);
        $ability = 'ability';
        $target = 'target';
        $extra1 = 'extra1';
        $extra2 = 'extra2';

        $overrides
            ->expects($this->once())
            ->method('shouldOverride')
            ->with($user, $ability, $target, $extra1, $extra2);

        $policy->setTargetSelfOverrides($overrides);

        $policy->before($user, $ability, $target, $extra1, $extra2);
    }
}
