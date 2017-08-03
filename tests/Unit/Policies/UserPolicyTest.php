<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit;

use Illuminate\Container\Container;
use Illuminate\Auth\Access\Gate;
use UnstoppableCarl\Arbiter\Tests\TestCase;
use UnstoppableCarl\Arbiter\Tests\App\Models\User;
use UnstoppableCarl\Arbiter\Tests\App\Policies\UserPolicy;

/**
 * Class UserPolicyTest
 * @package UnstoppableCarl\Arbiter\Tests\Unit
 * @ig
 */
class UserPolicyTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->markTestSkipped(
            'TODO'
        );
    }

    protected function freshGate($user, array $data, array $overrides = null)
    {
        $container = new Container();

        $userResolver = function () use ($user) {
            return $user;
        };

        $gate = new Gate($container, $userResolver);
        $gate->policy(User::class, UserPolicy::class);

        return $gate;
    }

    public function testGetters()
    {
        $this->assertTrue(true);
    }
}
