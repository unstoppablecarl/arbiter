<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit\Policies\Concerns;

use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Tests\App\Policies\UserPolicyWithAbilities;
use UnstoppableCarl\Arbiter\Tests\TestCase;

#[CoversTrait(\UnstoppableCarl\Arbiter\Policies\Concerns\HasAbilities::class)]
class HasAbilitiesTest extends TestCase
{
    protected function mockUser($primaryRole)
    {
        return $this->mockUserWithPrimaryRole($primaryRole);
    }

    static public function abilityWithTarget()
    {
        return [
            ['view', 'canView'],
            ['update', 'canUpdate'],
            ['delete', 'canDelete'],
            ['changePrimaryRoleFrom', 'canChangePrimaryRoleFrom'],
            ['changePrimaryRoleTo', 'canChangePrimaryRoleTo'],
        ];
    }

    #[DataProvider('abilityWithTarget')]
    public function testAbility($method, $userAuthorityMethod)
    {
        $sourcePrimaryRole = 'source_primary_role';
        $source = $this->mockUser($sourcePrimaryRole);

        $userAuthority = $this->createMock(UserAuthorityContract::class);
        $policy = new UserPolicyWithAbilities($userAuthority);

        $userAuthority
            ->expects($this->once())
            ->method($userAuthorityMethod)
            ->with($sourcePrimaryRole);

        $policy->{$method}($source);
    }

    #[DataProvider('abilityWithTarget')]
    public function testAbilityWithStringTarget($method, $userAuthorityMethod)
    {
        $sourcePrimaryRole = 'source_primary_role';
        $source = $this->mockUser($sourcePrimaryRole);
        $target = 'target_primary_role';

        $userAuthority = $this->createMock(UserAuthorityContract::class);
        $policy = new UserPolicyWithAbilities($userAuthority);

        $userAuthority
            ->expects($this->once())
            ->method($userAuthorityMethod)
            ->with($sourcePrimaryRole, $target);

        $policy->{$method}($source, $target);
    }

    #[DataProvider('abilityWithTarget')]
    public function testAbilityWithUserTarget($method, $userAuthorityMethod)
    {
        $sourcePrimaryRole = 'source_primary_role';
        $source = $this->mockUser($sourcePrimaryRole);
        $targetPrimaryRole = 'target_primary_role';
        $target = $this->mockUser($targetPrimaryRole);

        $userAuthority = $this->createMock(UserAuthorityContract::class);
        $policy = new UserPolicyWithAbilities($userAuthority);

        $userAuthority
            ->expects($this->once())
            ->method($userAuthorityMethod)
            ->with($sourcePrimaryRole, $targetPrimaryRole);

        $policy->{$method}($source, $target);
    }

    public function testChangePrimaryRoleWithUserTarget()
    {
        $sourcePrimaryRole = 'source_primary_role';
        $source = $this->mockUser($sourcePrimaryRole);
        $targetPrimaryRole = 'target_primary_role';
        $target = $this->mockUser($targetPrimaryRole);
        $primaryRole = 'new_primary_role';

        $userAuthority = $this->createMock(UserAuthorityContract::class);
        $policy = new UserPolicyWithAbilities($userAuthority);

        $userAuthority
            ->expects($this->once())
            ->method('canChangePrimaryRole')
            ->with($sourcePrimaryRole, $targetPrimaryRole, $primaryRole);

        $policy->changePrimaryRole($source, $target, $primaryRole);
    }


    public function testChangePrimaryRoleWithStringTarget()
    {
        $sourcePrimaryRole = 'source_primary_role';
        $source = $this->mockUser($sourcePrimaryRole);
        $target = 'target_primary_role';
        $primaryRole = 'new_primary_role';

        $userAuthority = $this->createMock(UserAuthorityContract::class);
        $policy = new UserPolicyWithAbilities($userAuthority);

        $userAuthority
            ->expects($this->once())
            ->method('canChangePrimaryRole')
            ->with($sourcePrimaryRole, $target, $primaryRole);

        $policy->changePrimaryRole($source, $target, $primaryRole);
    }

    public function testCreate()
    {
        $sourcePrimaryRole = 'source_primary_role';
        $source = $this->mockUser($sourcePrimaryRole);
        $primaryRole = 'new_primary_role';

        $userAuthority = $this->createMock(UserAuthorityContract::class);
        $policy = new UserPolicyWithAbilities($userAuthority);

        $userAuthority
            ->expects($this->once())
            ->method('canCreate')
            ->with($sourcePrimaryRole, $primaryRole);

        $policy->create($source, $primaryRole);
    }
}
