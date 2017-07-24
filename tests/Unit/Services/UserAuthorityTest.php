<?php

namespace Tests\Unit\Services;

use Illuminate\Container\Container;
use Illuminate\Auth\Access\Gate;
use PHPUnit\Framework\TestCase;
use UnstoppableCarl\Arbiter\UserAuthority;
use UnstoppableCarl\Arbiter\Tests\App\Models\User;
use UnstoppableCarl\Arbiter\Tests\App\Policies\UserPolicy;
use UnstoppableCarl\Arbiter\UserAuthority as Abilities;

class UserAuthorityTest extends TestCase
{
    protected function user($id, $primaryRole)
    {
        $user = new User([
            'id'              => $id,
            'primaryRoleName' => $primaryRole,
        ]);

        return $user;
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicCrud()
    {

        $data = [
            'admin'      => [
                'permissions' => [
                    Abilities::CAN_VIEW_USERS_WITH_PRIMARY_ROLE                   => ['admin', 'manager'],
                    Abilities::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO                => ['admin', 'manager'],
                    Abilities::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE => ['manager'],
                    Abilities::CAN_CREATE_USERS_WITH_PRIMARY_ROLE                 => ['manager'],
                    Abilities::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE                 => [],
                    Abilities::CAN_DELETE_USERS_WITH_PRIMARY_ROLE                 => ['manager', 'subscriber'],
                ],
            ],
            'manager'    => [
                'permissions' => [
                    Abilities::CAN_VIEW_USERS_WITH_PRIMARY_ROLE   => ['manager', 'subscriber'],
                    Abilities::CAN_CREATE_USERS_WITH_PRIMARY_ROLE => ['subscriber'],
                ],
            ],
            'subscriber' => [
                'permissions' => [
                ],
            ],
        ];

        $abilities = new UserAuthority($data);
        $policy    = new UserPolicy($abilities);

        $admin      = $this->user(1, 'admin');
        $manager    = $this->user(2, 'manager');
        $subscriber = $this->user(2, 'subscriber');

        $this->assertEquals(true, $policy->create($admin), 'can create any');
        $this->assertEquals(false, $policy->create($admin, 'admin'), 'can not create admin');
        $this->assertEquals(true, $policy->create($admin, 'manager'), 'can create manager');
        $this->assertEquals(false, $policy->create($admin, 'subscriber'), 'can not create subscriber');

        $this->assertEquals(true, $policy->view($admin), 'can view any');
        $this->assertEquals(true, $policy->view($admin, $admin), 'can view admin');
        $this->assertEquals(true, $policy->view($admin, $manager), 'can view manager');
        $this->assertEquals(false, $policy->view($admin, $subscriber), 'can not view subscriber');

        $this->assertEquals(false, $policy->update($admin), 'can not update any');
        $this->assertEquals(false, $policy->update($admin, $admin));
        $this->assertEquals(false, $policy->update($admin, $manager));
        $this->assertEquals(false, $policy->update($admin, $subscriber));

        $this->assertEquals(true, $policy->delete($admin), 'can delete any');
        $this->assertEquals(false, $policy->delete($admin, $admin));
        $this->assertEquals(true, $policy->delete($admin, $manager));
        $this->assertEquals(true, $policy->delete($admin, $subscriber));

        $this->assertEquals(false, $policy->changePrimaryRole($admin, $admin, 'admin'));
        $this->assertEquals(true, $policy->changePrimaryRole($admin, $manager, 'manager'));
        $this->assertEquals(false, $policy->changePrimaryRole($admin, $manager, 'subscriber'));
        $this->assertEquals(false, $policy->changePrimaryRole($admin, $subscriber, 'admin'));

        $this->assertEquals(true, $policy->changePrimaryRoleFrom($admin));
        $this->assertEquals(false, $policy->changePrimaryRoleFrom($admin, 'admin'));
        $this->assertEquals(true, $policy->changePrimaryRoleFrom($admin, 'manager'));
        $this->assertEquals(false, $policy->changePrimaryRoleFrom($admin, 'subscriber'));
        $this->assertEquals(false, $policy->changePrimaryRoleFrom($admin, 'admin'));


        $this->assertEquals(true, $policy->changePrimaryRoleTo($admin));
        $this->assertEquals(true, $policy->changePrimaryRoleTo($admin, 'admin'));
        $this->assertEquals(true, $policy->changePrimaryRoleTo($admin, 'manager'));
        $this->assertEquals(false, $policy->changePrimaryRoleTo($admin, 'subscriber'));
        $this->assertEquals(true, $policy->changePrimaryRoleTo($admin, 'admin'));
    }

    public function testGetters()
    {

        $data = [
            'admin'      => [
                'permissions' => [
                    Abilities::CAN_VIEW_USERS_WITH_PRIMARY_ROLE                   => ['admin', 'manager'],
                    Abilities::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO                => ['admin', 'manager'],
                    Abilities::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE => ['manager'],
                    Abilities::CAN_CREATE_USERS_WITH_PRIMARY_ROLE                 => ['manager'],
                    Abilities::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE                 => [],
                    Abilities::CAN_DELETE_USERS_WITH_PRIMARY_ROLE                 => ['manager', 'subscriber'],
                ],
            ],
            'manager'    => [
                'permissions' => [
                    Abilities::CAN_VIEW_USERS_WITH_PRIMARY_ROLE   => ['manager', 'subscriber'],
                    Abilities::CAN_CREATE_USERS_WITH_PRIMARY_ROLE => ['subscriber'],
                ],
            ],
            'subscriber' => [
                'permissions' => [
                ],
            ],
        ];

        $abilities = new UserAuthority($data);
        $policy    = new UserPolicy($abilities);

        $admin      = $this->user(1, 'admin');
        $manager    = $this->user(2, 'manager');
        $subscriber = $this->user(2, 'subscriber');

        $expected = [];
        $actual   = $policy->getViewablePrimaryRoles($subscriber);
        $this->assertEquals($expected, $actual);

        $expected = ['admin', 'manager'];
        $actual   = $policy->getViewablePrimaryRoles($admin);
        $this->assertEquals($expected, $actual);

        $expected = [];
        $actual   = $policy->getCreatablePrimaryRoles($subscriber);
        $this->assertEquals($expected, $actual);

        $expected = ['manager'];
        $actual   = $policy->getCreatablePrimaryRoles($admin);
        $this->assertEquals($expected, $actual);

        $actual = $policy->getCreatablePrimaryRoles('admin');
        $this->assertEquals($expected, $actual);

        $expected = ['subscriber'];
        $actual   = $policy->getCreatablePrimaryRoles($manager);
        $this->assertEquals($expected, $actual);

        $actual = $policy->getCreatablePrimaryRoles('manager');
        $this->assertEquals($expected, $actual);


        $expected = ['manager'];
        $actual   = $policy->getUpdatableFromPrimaryRoles($admin);
        $this->assertEquals($expected, $actual);

        $actual = $policy->getUpdatableFromPrimaryRoles('admin');
        $this->assertEquals($expected, $actual);


        $expected = ['admin', 'manager'];
        $actual   = $policy->getUpdatableToPrimaryRoles($admin);
        $this->assertEquals($expected, $actual);

        $actual = $policy->getUpdatableToPrimaryRoles('admin');
        $this->assertEquals($expected, $actual);

        $expected = [];
        $actual   = $policy->getUpdatableToPrimaryRoles($admin, 'admin');
        $this->assertEquals($expected, $actual);

        $expected = ['admin', 'manager'];
        $actual   = $policy->getUpdatableToPrimaryRoles('admin', 'manager');
        $this->assertEquals($expected, $actual);

        $expected = ['manager', 'subscriber'];
        $actual   = $policy->getDeletablePrimaryRoles('admin');
        $this->assertEquals($expected, $actual);

        $expected = ['admin', 'manager', 'subscriber'];
        $actual   = $policy->getPrimaryRoles();
        $this->assertEquals($expected, $actual);
    }

    protected function freshGate($user, array $data, array $overrides = [])
    {

        $container = new Container();

        $userResolver = function () use ($user) {
            return $user;
        };

        $gate = new Gate($container, $userResolver);
        $gate->policy(User::class, UserPolicy::class);

        $container->bind(UserPolicy::class, function () use ($data, $overrides) {
            $abilities = new UserAuthority($data);
            return new UserPolicy($abilities, $overrides);
        });

        return $gate;
    }

    public function testOverrideWhenSelf()
    {
        $user = $this->user(1, 'admin');

        $data = [
            'superuser' => [
                'permissions' => [

                ],
            ],
            'admin'     => [
                'permissions' => [
                    Abilities::CAN_VIEW_USERS_WITH_PRIMARY_ROLE                   => [],
                    Abilities::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE                 => [],
                    Abilities::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE                 => ['admin'],
                    Abilities::CAN_DELETE_USERS_WITH_PRIMARY_ROLE                 => ['admin'],
                    Abilities::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE => ['admin'],
                    Abilities::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO                => ['admin', 'superuser'],
                ],
            ],
        ];

        $gate = $this->freshGate($user, $data, [
            'view'              => true,
            'update'            => true,
            'delete'            => false,
            'changePrimaryRole' => false,
            'foo'               => true,
        ]);

        $expected = false;
        $actual   = $gate->allows('view', User::class);
        $msg      = 'can correctly check view of User::class when override set to always view self';
        $this->assertEquals($expected, $actual, $msg);

        $expected = false;
        $actual   = $gate->allows('foo', User::class);
        $msg      = 'can correctly ignore overrides that do not match a policy method';
        $this->assertEquals($expected, $actual, $msg);

        $expected = false;
        $actual   = $gate->allows('bar', User::class);
        $msg      = 'can correctly ignore absent abilities that are not overridden';
        $this->assertEquals($expected, $actual, $msg);

        $expected = true;
        $actual   = $gate->allows('view', $user);
        $this->assertEquals($expected, $actual);

        $expected = true;
        $actual   = $gate->allows('update', $user);
        $this->assertEquals($expected, $actual);

        $expected = false;
        $actual   = $gate->allows('delete', $user);
        $this->assertEquals($expected, $actual);

        $expected = false;
        $actual   = $gate->allows('changePrimaryRole', $user);
        $msg      = 'can override default change role of self';
        $this->assertEquals($expected, $actual, $msg);

        $expected = true;
        $actual   = $gate->allows('customAbilityTrue', $user);
        $msg      = 'can fallback to methods with no override key defined';
        $this->assertEquals($expected, $actual, $msg);

        $expected = false;
        $actual   = $gate->allows('customAbilityFalse', $user);
        $msg      = 'can fallback to methods with no override key defined';
        $this->assertEquals($expected, $actual, $msg);
    }
}
