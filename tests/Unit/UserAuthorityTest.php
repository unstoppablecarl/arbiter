<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit;

use UnstoppableCarl\Arbiter\Tests\App\Models\User;
use UnstoppableCarl\Arbiter\Tests\TestCase;
use UnstoppableCarl\Arbiter\UserAuthority;

/**
 * Class UserAuthorityTest
 * @covers \UnstoppableCarl\Arbiter\UserAuthority::__construct()
 * @covers \UnstoppableCarl\Arbiter\UserAuthority::parseData()
 * * @covers \UnstoppableCarl\Arbiter\UserAuthority::set()
 */
class UserAuthorityTest extends TestCase
{
    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::getPrimaryRoles()
     */
    public function testGetPrimaryRoles()
    {
        $data = [
            'admin'   => [
                'ability' => ['manager', 'subscriber'],
            ],
            'manager' => [
                'ability' => ['hacker'],
            ],
            'hacker'  => [
                'ability' => ['admin'],
            ],
        ];

        $userAuth = new UserAuthority($data);
        $expected = array_keys($data);
        $actual   = $userAuth->getPrimaryRoles();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::getPrimaryRoles()
     */
    public function testGetPrimaryRolesWhenFalsy()
    {
        $data = [
            'admin'      => null,
            'manager'    => false,
            'subscriber' => 0,
            'hacker'     => [],
        ];

        $userAuth = new UserAuthority($data);
        $expected = array_keys($data);
        $actual   = $userAuth->getPrimaryRoles();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::can()
     */
    public function testCan()
    {
        $data = [
            'role_1' => [
                'ability' => ['role_2', 'role_3', 'not_set_role'],
            ],
            'role_2' => [
                'ability' => ['role_2', 'role_3'],
            ],
            'role_3' => [],

        ];

        $userAuth = new UserAuthority($data);

        $this->assertSame(false, $userAuth->can('role_1', 'ability', 'role_1'));
        $this->assertSame(true, $userAuth->can('role_1', 'ability', 'role_2'));
        $this->assertSame(true, $userAuth->can('role_1', 'ability', 'role_3'));
        $this->assertSame(true, $userAuth->can('role_1', 'ability', 'not_set_role'));

        $this->assertSame(false, $userAuth->can('role_2', 'ability', 'role_1'));
        $this->assertSame(true, $userAuth->can('role_2', 'ability', 'role_2'));
        $this->assertSame(true, $userAuth->can('role_2', 'ability', 'role_3'));
        $this->assertSame(false, $userAuth->can('role_2', 'ability', 'not_set_role'));

        $this->assertSame(false, $userAuth->can('role_3', 'ability', 'role_1'));
        $this->assertSame(false, $userAuth->can('role_3', 'ability', 'role_2'));
        $this->assertSame(false, $userAuth->can('role_3', 'ability', 'role_3'));
        $this->assertSame(false, $userAuth->can('role_3', 'ability', 'not_set_role'));
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::can()
     */
    public function testCanWithStringTarget()
    {
        $data = [
            'role_1' => [
                'ability_1' => 'role_2',
            ],
            'role_2' => [],

        ];

        $userAuth = new UserAuthority($data);

        $this->assertSame(false, $userAuth->can('role_1', 'ability_1', 'role_1'));
        $this->assertSame(true, $userAuth->can('role_1', 'ability_1', 'role_2'));
        $this->assertSame(false, $userAuth->can('role_1', 'ability_1', 'not_set_role'));
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::can()
     */
    public function testCanWithFalsyTargets()
    {
        $data = [
            'role_1' => [
                'ability_1' => false,
                'ability_2' => null,
                'ability_3' => 0,
                'ability_4' => [],
            ],
        ];

        $userAuth = new UserAuthority($data);

        $this->assertSame(false, $userAuth->can('role_1', 'ability_1', 'role_2'));
        $this->assertSame(false, $userAuth->can('role_1', 'ability_2', 'role_2'));
        $this->assertSame(false, $userAuth->can('role_1', 'ability_3', 'role_2'));
        $this->assertSame(false, $userAuth->can('role_1', 'ability_4', 'role_2'));
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::canAny()
     */
    public function testCanAny()
    {
        $data = [
            'role_1' => [
                'ability_1' => ['role_2', 'role_3', 'not_set_role'],
                'ability_2' => [],
            ],
        ];

        $userAuth = new UserAuthority($data);

        $this->assertSame(true, $userAuth->canAny('role_1', 'ability_1'));
        $this->assertSame(false, $userAuth->canAny('role_1', 'ability_2'));
        $this->assertSame(false, $userAuth->canAny('role_1', 'not_set_ability'));
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::canAny()
     */
    public function testCanAnyWithFalsyTargets()
    {
        $data = [
            'role_1' => [
                'ability_1' => false,
                'ability_2' => null,
                'ability_3' => 0,
                'ability_4' => [],
            ],
        ];

        $userAuth = new UserAuthority($data);

        $this->assertSame(false, $userAuth->canAny('role_1', 'ability_1'));
        $this->assertSame(false, $userAuth->canAny('role_1', 'ability_2'));
        $this->assertSame(false, $userAuth->canAny('role_1', 'ability_3'));
        $this->assertSame(false, $userAuth->canAny('role_1', 'ability_4'));
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::canAny()
     */
    public function testCanAnyWithFalsyAbilities()
    {
        $data = [
            'role_1' => false,
            'role_2' => null,
            'role_3' => 0,
            'role_4' => [],
        ];

        $userAuth = new UserAuthority($data);

        $this->assertSame(false, $userAuth->canAny('role_1', 'not_set_ability'));
        $this->assertSame(false, $userAuth->canAny('role_2', 'not_set_ability'));
        $this->assertSame(false, $userAuth->canAny('role_3', 'not_set_ability'));
        $this->assertSame(false, $userAuth->canAny('role_4', 'not_set_ability'));
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::canOrAny()
     */
    public function testCanOrAnyWithValue()
    {
        $data = [
            'role_1' => [
                'ability' => ['role_2', 'role_3', 'not_set_role'],
            ],
            'role_2' => [
                'ability' => ['role_2', 'role_3'],
            ],
            'role_3' => [],
        ];

        $userAuth = new UserAuthority($data);

        $this->assertSame(false, $userAuth->canOrAny('role_1', 'ability', 'role_1'));
        $this->assertSame(true, $userAuth->canOrAny('role_1', 'ability', 'role_2'));
        $this->assertSame(true, $userAuth->canOrAny('role_1', 'ability', 'role_3'));
        $this->assertSame(true, $userAuth->canOrAny('role_1', 'ability', 'not_set_role'));

        $this->assertSame(false, $userAuth->canOrAny('role_2', 'ability', 'role_1'));
        $this->assertSame(true, $userAuth->canOrAny('role_2', 'ability', 'role_2'));
        $this->assertSame(true, $userAuth->canOrAny('role_2', 'ability', 'role_3'));
        $this->assertSame(false, $userAuth->canOrAny('role_2', 'ability', 'not_set_role'));

        $this->assertSame(false, $userAuth->canOrAny('role_3', 'ability', 'role_1'));
        $this->assertSame(false, $userAuth->canOrAny('role_3', 'ability', 'role_2'));
        $this->assertSame(false, $userAuth->canOrAny('role_3', 'ability', 'role_3'));
        $this->assertSame(false, $userAuth->canOrAny('role_3', 'ability', 'not_set_role'));

        $msg = 'canOrAny without target';
        $this->assertSame(true, $userAuth->canOrAny('role_1', 'ability'), $msg);
        $this->assertSame(true, $userAuth->canOrAny('role_2', 'ability'), $msg);
        $this->assertSame(false, $userAuth->canOrAny('role_3', 'ability'), $msg);
        $this->assertSame(false, $userAuth->canOrAny('not_set_role', 'ability'), $msg);
    }

    public function canMethodConstantProvider()
    {
        $data = [
            'canCreate'                => UserAuthority::CAN_CREATE_USERS_WITH_PRIMARY_ROLE,
            'canView'                  => UserAuthority::CAN_VIEW_USERS_WITH_PRIMARY_ROLE,
            'canUpdate'                => UserAuthority::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE,
            'canDelete'                => UserAuthority::CAN_DELETE_USERS_WITH_PRIMARY_ROLE,
            'canChangePrimaryRoleFrom' => UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE,
            'canChangePrimaryRoleTo'   => UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO,
        ];

        $out = [];
        foreach ($data as $method => $key) {
            $out[] = [$method, $key];
        }
        return $out;
    }

    /**
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::canCreate()
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::canView()
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::canUpdate()
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::canDelete()
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::canChangePrimaryRoleFrom()
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::canChangePrimaryRoleTo()
     * @dataProvider canMethodConstantProvider
     * @param $method
     * @param $key
     */
    public function testMappedMethods($method, $key)
    {
        $data     = [
            'role_1' => [
                $key => ['role_2', 'role_3', 'not_set_role'],
            ],
            'role_2' => [
                $key => ['role_2', 'role_3'],
            ],
            'role_3' => [],
        ];
        $userAuth = new UserAuthority($data);

        $msg = $method . ' with target';
        $this->assertSame(false, $userAuth->{$method}('role_1', 'role_1'), $msg);
        $this->assertSame(true, $userAuth->{$method}('role_1', 'role_2'), $msg);
        $this->assertSame(true, $userAuth->{$method}('role_1', 'role_3'), $msg);
        $this->assertSame(true, $userAuth->{$method}('role_1', 'not_set_role'), $msg);

        $this->assertSame(false, $userAuth->{$method}('role_2', 'role_1'), $msg);
        $this->assertSame(true, $userAuth->{$method}('role_2', 'role_2'), $msg);
        $this->assertSame(true, $userAuth->{$method}('role_2', 'role_3'), $msg);
        $this->assertSame(false, $userAuth->{$method}('role_2', 'not_set_role'), $msg);

        $this->assertSame(false, $userAuth->{$method}('role_3', 'role_1'), $msg);
        $this->assertSame(false, $userAuth->{$method}('role_3', 'role_2'), $msg);
        $this->assertSame(false, $userAuth->{$method}('role_3', 'role_3'), $msg);
        $this->assertSame(false, $userAuth->{$method}('role_3', 'not_set_role'), $msg);

        $msg = $method . ' without target';
        $this->assertSame(true, $userAuth->{$method}('role_1'), $msg);
        $this->assertSame(true, $userAuth->{$method}('role_2'), $msg);
        $this->assertSame(false, $userAuth->{$method}('role_3'), $msg);
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::canChangePrimaryRole()
     */
    public function testCanChangePrimaryRole()
    {
        $data     = [
            'role_1' => [
                UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE => ['role_2'],
                UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO                => ['role_3'],
            ],
        ];
        $userAuth = new UserAuthority($data);

        $this->assertSame(false, $userAuth->canChangePrimaryRole('role_1', 'role_1', 'role_1'));
        $this->assertSame(false, $userAuth->canChangePrimaryRole('role_1', 'role_2', 'role_2'));
        $this->assertSame(false, $userAuth->canChangePrimaryRole('role_1', 'role_3', 'role_3'));
        $this->assertSame(false, $userAuth->canChangePrimaryRole('role_1', 'role_1', 'role_2'));
        $this->assertSame(false, $userAuth->canChangePrimaryRole('role_1', 'role_1', 'role_3'));
        $this->assertSame(true, $userAuth->canChangePrimaryRole('role_1', 'role_2', 'role_3'));
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::get()
     */
    public function testGet()
    {
        $data = [
            'admin' => [
                'ability_1' => ['admin', 'manager'],
            ],
        ];

        $userAuth = new UserAuthority($data);

        $expected = [];
        $actual   = $userAuth->get('admin', 'non_valid');
        $msg      = 'non set ability returns false';
        $this->assertEquals($expected, $actual, $msg);

        $expected = ['admin', 'manager'];
        $msg      = 'valid ability returns target array';
        $actual   = $userAuth->get('admin', 'ability_1');
        $this->assertEquals($expected, $actual, $msg);
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::get()
     */
    public function testGetWithStringTarget()
    {
        $data = [
            'admin' => [
                'ability_1' => 'subscriber',
            ],
        ];

        $userAuth = new UserAuthority($data);

        $expected = ['subscriber'];
        $actual   = $userAuth->get('admin', 'ability_1');
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::get()
     */
    public function testGetWhenFalsy()
    {
        $data = [
            'admin' => [
                'ability_1' => 'subscriber',
            ],
        ];

        $userAuth = new UserAuthority($data);

        $expected = ['subscriber'];
        $actual   = $userAuth->get('admin', 'ability_1');
        $this->assertEquals($expected, $actual);
    }


    public function getMethodConstantProvider()
    {
        $data = [
            'getViewablePrimaryRoles'       => UserAuthority::CAN_VIEW_USERS_WITH_PRIMARY_ROLE,
            'getCreatablePrimaryRoles'      => UserAuthority::CAN_CREATE_USERS_WITH_PRIMARY_ROLE,
            'getUpdatablePrimaryRoles'      => UserAuthority::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE,
            'getDeletablePrimaryRoles'      => UserAuthority::CAN_DELETE_USERS_WITH_PRIMARY_ROLE,
            'getChangeableFromPrimaryRoles' => UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE,
            'getChangeableToPrimaryRoles'   => UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO,
        ];

        $out = [];
        foreach ($data as $method => $key) {
            $out[] = [$method, $key];
        }
        return $out;
    }

    /**
     * @dataProvider getMethodConstantProvider
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::getViewablePrimaryRoles()
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::getCreatablePrimaryRoles()
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::getUpdatablePrimaryRoles()
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::getDeletablePrimaryRoles()
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::getChangeableFromPrimaryRoles()
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::getChangeableToPrimaryRoles()
     */
    public function testGetViewablePrimaryRoles($method, $key)
    {
        $data     = [
            'role_1' => [
                $key => ['role_2', 'role_3', 'not_set_role'],
            ],
            'role_2' => [
                $key => ['role_2', 'role_3'],
            ],
            'role_3' => [],
        ];
        $userAuth = new UserAuthority($data);

        $expected = $data['role_1'][$key];
        $actual   = $userAuth->{$method}('role_1');
        $this->assertSame($expected, $actual);

        $expected = $data['role_2'][$key];
        $actual   = $userAuth->{$method}('role_2');
        $this->assertSame($expected, $actual);

        $expected = [];
        $actual   = $userAuth->{$method}('role_3');
        $this->assertSame($expected, $actual);
    }

    public function getChangeableToPrimaryRolesWithTarget()
    {
        $out = [
            ['role_1'],
            ['role_2', 'role_3'],
            [
            ],
        ];

        return array_map(function ($item) {
            return [$item];
        }, $out);
    }

    /**
     * @covers \UnstoppableCarl\Arbiter\UserAuthority::getChangeableToPrimaryRoles()
     * @dataProvider getChangeableToPrimaryRolesWithTarget()
     */
    public function testGetChangeableToPrimaryRolesWithTarget($toRoles)
    {
        $data = [
            'source_role_1' => [
                UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE => 'from_role',
                UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO                => $toRoles,
            ],
        ];

        $userAuth = new UserAuthority($data);
        $expected = $toRoles ?: [];
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'from_role'));
    }

    public function getChangeableToPrimaryRolesWhenCannotChangeFrom()
    {
        $from = UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE;
        $to   = UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO;

        $out = [
            [
                $from => ['role_2'],
                $to   => ['role_3', 'role_4'],
            ],
            [
                $from => [],
                $to   => ['role_3', 'role_4'],
            ],
            [
                $to => ['role_3', 'role_4'],
            ],
        ];

        return array_map(function ($item) {
            return [$item];
        }, $out);
    }

    /**
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::getChangeableToPrimaryRoles()
     * @dataProvider getChangeableToPrimaryRolesWhenCannotChangeFrom()
     */
    public function testGetChangeableToPrimaryRolesWhenCannotChangeFrom(array $abilities)
    {
        $data     = [
            'source_role_1' => $abilities,

        ];
        $userAuth = new UserAuthority($data);

        $expected = [];
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'source_role_1'));
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'not_set_role'));
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'role_3'));
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'role_4'));
    }

    public function getChangeableToPrimaryRolesWhenCannotChangeTo()
    {
        $from = UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE;
        $to   = UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO;

        $out = [
            [
                $from => ['role_2'],
                $to   => [],
            ],
            [
                $from => [],
                $to   => [],
            ],
            [
                $from => ['role_2'],
            ],
        ];

        return array_map(function ($item) {
            return [$item];
        }, $out);
    }

    /**
     * @covers       \UnstoppableCarl\Arbiter\UserAuthority::getChangeableToPrimaryRoles()
     * @dataProvider getChangeableToPrimaryRolesWhenCannotChangeTo()
     */
    public function testGetChangeableToPrimaryRolesWhenCannotChangeTo(array $abilities)
    {
        $data     = [
            'source_role_1' => $abilities,

        ];
        $userAuth = new UserAuthority($data);

        $expected = [];
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'source_role_1'));
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'not_set_role'));
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'role_3'));
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'role_4'));
    }
}
