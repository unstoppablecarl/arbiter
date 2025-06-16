<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit\Policies\Concerns;

use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole;
use UnstoppableCarl\Arbiter\Tests\App\Policies\UserPolicyWithUserAuthority;
use UnstoppableCarl\Arbiter\Tests\TestCase;

#[CoversTrait(\UnstoppableCarl\Arbiter\Policies\Concerns\HasUserAuthority::class)]
class HasUserAuthorityTest extends TestCase
{
    public function testToPrimaryRoleWithUser()
    {
        $user = $this->createMock(UserWithPrimaryRole::class);
        $policy = new UserPolicyWithUserAuthority();

        $user
            ->expects($this->once())
            ->method('getPrimaryRoleName')
            ->willReturn('admin');

        $expected = 'admin';
        $actual = $policy->toPrimaryRole($user);
        $this->assertSame($expected, $actual);
    }

    static public function toPrimaryRoleProvider()
    {
        return [
            [null],
            ['string'],
        ];
    }

    #[DataProvider('toPrimaryRoleProvider')]
    public function testToPrimaryRole($value)
    {
        $policy = new UserPolicyWithUserAuthority();

        $actual = $policy->toPrimaryRole($value);
        $this->assertSame($value, $actual);
    }

    public function testUserAuthority()
    {
        $userAuthority = $this->createMock(UserAuthorityContract::class);
        $policy = new UserPolicyWithUserAuthority();
        $policy->setUserAuthority($userAuthority);

        $this->assertSame($userAuthority, $policy->userAuthority());
    }
}
