<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit\Policies;

use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\Tests\TestCase;

/**
 * @covers \UnstoppableCarl\Arbiter\TargetSelfOverrides
 */
class TargetSelfOverridesTest extends TestCase
{

    public function abilityValueProvider()
    {
        return $this->wrapSingleArgumentProviderData([
            [true],
            [false],
            [null],
        ]);
    }

    /**
     * @dataProvider abilityValueProvider()
     * @param $value
     */
    public function testDifferentUsers($value)
    {
        $policy = new TargetSelfOverrides([
            'ability' => $value,
        ]);
        $user1  = $this->freshUser(1, 'admin');
        $user2  = $this->freshUser(2, 'admin');

        $actual = $policy->before($user1, 'ability', $user2);
        $msg    = 'correctly identifies different users and ignores overrides';
        $this->assertNull($actual, $msg);
    }

    /**
     * @dataProvider abilityValueProvider()
     * @param $value
     */
    public function testSameUser($value)
    {
        $policy = new TargetSelfOverrides([
            'ability' => $value,
        ]);
        $user   = $this->freshUser(1, 'admin');
        $msg    = 'correctly identifies different users and ignores overrides';

        $expected = $value;
        $actual   = $policy->before($user, 'ability', $user);
        $this->assertSame($expected, $actual, $msg);
    }

    public function testSameUserWhenEmpty()
    {
        $policy = new TargetSelfOverrides([]);
        $user   = $this->freshUser(1, 'admin');

        $actual = $policy->before($user, 'ability', $user);
        $msg    = 'returns null when same user but no overrides set';
        $this->assertNull($actual, $msg);
    }

    /**
     * @dataProvider abilityValueProvider()
     * @param $value
     */
    public function testNonUser($value)
    {
        $policy = new TargetSelfOverrides([
            'ability' => $value,
        ]);
        $user1  = $this->freshUser(1, 'admin');
        $user2  = new \stdClass();

        $msg    = 'returns null for non-user target';
        $actual = $policy->before($user1, 'ability', $user2);
        $this->assertNull($actual, $msg);
    }
}
