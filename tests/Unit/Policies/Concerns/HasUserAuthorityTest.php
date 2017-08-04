<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit\Policies\Concerns;

use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole;
use UnstoppableCarl\Arbiter\Tests\App\Policies\UserPolicyWithUserAuthority;
use UnstoppableCarl\Arbiter\Tests\TestCase;

/**
 * @covers \UnstoppableCarl\Arbiter\Policies\Concerns\HasUserAuthority
 */
class HasUserAuthorityTest extends TestCase
{
    /**
     * @covers \UnstoppableCarl\Arbiter\Policies\Concerns\HasUserAuthority::toPrimaryRole()
     */
    public function testToPrimaryRoleWithUser()
    {
        $user   = $this->createMock(UserWithPrimaryRole::class);
        $policy = new UserPolicyWithUserAuthority();

        $user
            ->expects($this->once())
            ->method('getPrimaryRoleName')
            ->willReturn('admin');

        $expected = 'admin';
        $actual   = $policy->toPrimaryRole($user);
        $this->assertSame($expected, $actual);
    }

    public function toPrimaryRoleProvider()
    {
        return [
            [null],
            [0],
            [1],
            [1.5],
            [99],
            [new \stdClass()],
            ['string'],
            [false],
            [true],
            [[]],
        ];
    }

    /**
     * @covers       \UnstoppableCarl\Arbiter\Policies\Concerns\HasUserAuthority::toPrimaryRole()
     * @dataProvider toPrimaryRoleProvider
     */
    public function testToPrimaryRole($value)
    {
        $policy = new UserPolicyWithUserAuthority();

        $actual = $policy->toPrimaryRole($value);
        $this->assertSame($value, $actual);
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\Policies\Concerns\HasUserAuthority::userAuthority()
     */
    public function testUserAuthority()
    {
        $userAuthority = $this->createMock(UserAuthorityContract::class);
        $policy        = new UserPolicyWithUserAuthority();
        $policy->setUserAuthority($userAuthority);

        $this->assertSame($userAuthority, $policy->userAuthority());
    }
}