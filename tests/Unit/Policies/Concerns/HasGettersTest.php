<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit\Policies\Concerns;

use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Tests\App\Policies\UserPolicyWithGetters;
use UnstoppableCarl\Arbiter\Tests\TestCase;

/**
 * @covers \UnstoppableCarl\Arbiter\Policies\Concerns\HasGetters
 */
class HasGettersTest extends TestCase
{
    protected function mockUser($primaryRole)
    {
        return $this->mockUserWithPrimaryRole($primaryRole);
    }

    public function testGet()
    {
        $userAuthority = $this->createMock(UserAuthorityContract::class);

        $policy = new UserPolicyWithGetters($userAuthority);

        $userAuthority
            ->expects($this->once())
            ->method('getPrimaryRoles');

        $policy->getPrimaryRoles();
    }

    public function getterProvider()
    {
        return [
            ['getViewablePrimaryRoles'],
            ['getCreatablePrimaryRoles'],
            ['getChangeableFromPrimaryRoles'],
            ['getChangeableToPrimaryRoles'],
            ['getDeletablePrimaryRoles'],
        ];
    }

    /**
     * @dataProvider getterProvider
     */
    public function testGetter($method)
    {
        $sourcePrimaryRole = 'source_primary_role';
        $source            = $this->mockUser($sourcePrimaryRole);

        $userAuthority = $this->createMock(UserAuthorityContract::class);
        $policy        = new UserPolicyWithGetters($userAuthority);

        $userAuthority
            ->expects($this->once())
            ->method($method)
            ->with($sourcePrimaryRole);

        $policy->{$method}($source);
    }

    public function testGetChangeableToPrimaryRolesWithUserTarget()
    {
        $method = 'getChangeableToPrimaryRoles';
        $sourcePrimaryRole = 'source_primary_role';
        $targetPrimaryRole = 'target_primary_role';
        $source            = $this->mockUser($sourcePrimaryRole);
        $target            = $this->mockUser($targetPrimaryRole);

        $userAuthority = $this->createMock(UserAuthorityContract::class);
        $policy        = new UserPolicyWithGetters($userAuthority);

        $userAuthority
            ->expects($this->once())
            ->method($method)
            ->with($sourcePrimaryRole, $targetPrimaryRole);

        $policy->{$method}($source, $target);
    }

    public function testGetChangeableToPrimaryRolesWithStringTarget()
    {
        $method = 'getChangeableToPrimaryRoles';
        $sourcePrimaryRole = 'source_primary_role';
        $source            = $this->mockUser($sourcePrimaryRole);
        $target = 'target_primary_role';
        $userAuthority = $this->createMock(UserAuthorityContract::class);
        $policy        = new UserPolicyWithGetters($userAuthority);

        $userAuthority
            ->expects($this->once())
            ->method($method)
            ->with($sourcePrimaryRole, $target);

        $policy->{$method}($source, $target);
    }
}