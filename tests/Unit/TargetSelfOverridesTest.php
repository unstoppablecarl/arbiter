<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\Tests\TestCase;

#[CoversClass(\UnstoppableCarl\Arbiter\TargetSelfOverrides::class)]
class TargetSelfOverridesTest extends TestCase
{

    protected function mockUser($id, $primaryRole)
    {
        return $this->mockUserWithAuthAndPrimaryRole($id, $primaryRole);
    }

    static public function abilityValueProvider(): array
    {
        return static::wrapSingleArgumentProviderData([
            true,
            false,
            null,
        ]);
    }

    #[DataProvider('abilityValueProvider')]
    public function testDifferentUsers($value)
    {
        $overrides = new TargetSelfOverrides([
            'ability' => $value,
        ]);
        $user1 = $this->mockUser(1, 'role_1');
        $user2 = $this->mockUser(2, 'role_2');

        $actual = $overrides->shouldOverride($user1, 'ability', $user2);
        $msg = 'correctly identifies different users and ignores overrides';
        $this->assertNull($actual, $msg);
    }

    #[DataProvider('abilityValueProvider')]
    public function testSameUser($value)
    {
        $overrides = new TargetSelfOverrides([
            'ability' => $value,
        ]);
        $user = $this->mockUser(1, 'role_1');
        $msg = 'correctly identifies same user and ignores overrides';

        $expected = $value;
        $actual = $overrides->shouldOverride($user, 'ability', $user);
        $this->assertSame($expected, $actual, $msg);
    }

    public function testSameUserWhenEmpty()
    {
        $overrides = new TargetSelfOverrides([]);
        $user = $this->mockUser(1, 'role_1');

        $actual = $overrides->shouldOverride($user, 'ability', $user);
        $msg = 'returns null when same user but no overrides set';
        $this->assertNull($actual, $msg);
    }


    public function testOtherUserIsPrimaryRoleString()
    {
        $overrides = new TargetSelfOverrides([]);
        $user = $this->mockUser(1, 'role_1');

        $actual = $overrides->shouldOverride($user, 'ability', 'some_primary_role');
        $msg = 'returns null when other user is a primary role string';
        $this->assertNull($actual, $msg);
    }
}
