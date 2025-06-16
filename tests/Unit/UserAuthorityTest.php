<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use UnstoppableCarl\Arbiter\Tests\TestCase;
use UnstoppableCarl\Arbiter\UserAuthority;

#[CoversClass(\UnstoppableCarl\Arbiter\UserAuthority::class)]
class UserAuthorityTest extends TestCase
{

    static public function falsyValueProvider()
    {
        return [
            [null],
            [false],
            [0],
            [[]],
        ];
    }

    public function testGetPrimaryRoles()
    {
        $data = [
            'admin' => [
                'ability' => ['manager', 'subscriber'],
            ],
            'manager' => [
                'ability' => ['hacker'],
            ],
            'hacker' => [
                'ability' => ['admin'],
            ],
        ];

        $userAuth = new UserAuthority($data);
        $expected = array_keys($data);
        $actual = $userAuth->getPrimaryRoles();
        $this->assertEquals($expected, $actual);
    }

    public function testGetPrimaryRolesWhenFalsy()
    {
        $data = [
            'admin' => null,
            'manager' => false,
            'subscriber' => 0,
            'hacker' => [],
        ];

        $userAuth = new UserAuthority($data);
        $expected = array_keys($data);
        $actual = $userAuth->getPrimaryRoles();
        $this->assertEquals($expected, $actual);
    }

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

    #[DataProvider('falsyValueProvider')]
    public function testCanWithFalsyTargets($value)
    {
        $data = [
            'role_1' => [
                'ability_1' => $value,
            ],
        ];

        $userAuth = new UserAuthority($data);

        $this->assertSame(false, $userAuth->can('role_1', 'ability_1', 'role_1'));
        $this->assertSame(false, $userAuth->can('role_1', 'ability_1', 'role_2'));
    }

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

    #[DataProvider('falsyValueProvider')]
    public function testCanAnyWithFalsyTargets($value)
    {
        $data = [
            'role_1' => [
                'ability_1' => $value,
            ],
        ];

        $userAuth = new UserAuthority($data);

        $this->assertSame(false, $userAuth->canAny('role_1', 'ability_1'));
    }

    #[DataProvider('falsyValueProvider')]
    public function testCanAnyWithFalsyAbilities($value)
    {
        $data = [
            'role_1' => $value,
        ];

        $userAuth = new UserAuthority($data);
        $this->assertSame(false, $userAuth->canAny('role_1', 'not_set_ability'));
    }

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

    static public function canMethodConstantProvider()
    {
        $data = [
            'canCreate' => UserAuthority::CAN_CREATE_USERS_WITH_PRIMARY_ROLE,
            'canView' => UserAuthority::CAN_VIEW_USERS_WITH_PRIMARY_ROLE,
            'canUpdate' => UserAuthority::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE,
            'canDelete' => UserAuthority::CAN_DELETE_USERS_WITH_PRIMARY_ROLE,
            'canChangePrimaryRoleFrom' => UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE,
            'canChangePrimaryRoleTo' => UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO,
        ];

        $out = [];
        foreach ($data as $method => $key) {
            $out[] = [$method, $key];
        }
        return $out;
    }

    #[DataProvider('canMethodConstantProvider')]
    public function testMappedMethods($method, $key)
    {
        $data = [
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

    public function testCanChangePrimaryRole()
    {
        $data = [
            'role_1' => [
                UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE => ['role_2'],
                UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO => ['role_3'],
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

    public function testGet()
    {
        $data = [
            'admin' => [
                'ability_1' => ['admin', 'manager'],
            ],
        ];

        $userAuth = new UserAuthority($data);

        $expected = [];
        $actual = $userAuth->get('admin', 'non_valid');
        $msg = 'non set ability returns false';
        $this->assertEquals($expected, $actual, $msg);

        $expected = ['admin', 'manager'];
        $msg = 'valid ability returns target array';
        $actual = $userAuth->get('admin', 'ability_1');
        $this->assertEquals($expected, $actual, $msg);
    }

    public function testGetWithStringTarget()
    {
        $data = [
            'admin' => [
                'ability_1' => 'subscriber',
            ],
        ];

        $userAuth = new UserAuthority($data);

        $expected = ['subscriber'];
        $actual = $userAuth->get('admin', 'ability_1');
        $this->assertEquals($expected, $actual);
    }

    public function testGetWhenFalsy()
    {
        $data = [
            'admin' => [
                'ability_1' => 'subscriber',
            ],
        ];

        $userAuth = new UserAuthority($data);

        $expected = ['subscriber'];
        $actual = $userAuth->get('admin', 'ability_1');
        $this->assertEquals($expected, $actual);
    }

    static public function getMethodConstantProvider()
    {
        $data = [
            'getViewablePrimaryRoles' => UserAuthority::CAN_VIEW_USERS_WITH_PRIMARY_ROLE,
            'getCreatablePrimaryRoles' => UserAuthority::CAN_CREATE_USERS_WITH_PRIMARY_ROLE,
            'getUpdatablePrimaryRoles' => UserAuthority::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE,
            'getDeletablePrimaryRoles' => UserAuthority::CAN_DELETE_USERS_WITH_PRIMARY_ROLE,
            'getChangeableFromPrimaryRoles' => UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE,
            'getChangeableToPrimaryRoles' => UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO,
        ];

        $out = [];
        foreach ($data as $method => $key) {
            $out[] = [$method, $key];
        }
        return $out;
    }

    #[DataProvider('getMethodConstantProvider')]
    public function testGetViewablePrimaryRoles($method, $key)
    {
        $data = [
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
        $actual = $userAuth->{$method}('role_1');
        $this->assertSame($expected, $actual);

        $expected = $data['role_2'][$key];
        $actual = $userAuth->{$method}('role_2');
        $this->assertSame($expected, $actual);

        $expected = [];
        $actual = $userAuth->{$method}('role_3');
        $this->assertSame($expected, $actual);
    }

    static public function getChangeableToPrimaryRolesWithTarget()
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

    #[DataProvider('getChangeableToPrimaryRolesWithTarget')]
    public function testGetChangeableToPrimaryRolesWithTarget($toRoles)
    {
        $data = [
            'source_role_1' => [
                UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE => 'from_role',
                UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO => $toRoles,
            ],
        ];

        $userAuth = new UserAuthority($data);
        $expected = $toRoles ?: [];
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'from_role'));
    }

    static public function getChangeableToPrimaryRolesWhenCannotChangeFrom()
    {
        $from = UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE;
        $to = UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO;

        $out = [
            [
                $from => ['role_2'],
                $to => ['role_3', 'role_4'],
            ],
            [
                $from => [],
                $to => ['role_3', 'role_4'],
            ],
            [
                $to => ['role_3', 'role_4'],
            ],
        ];

        return array_map(function ($item) {
            return [$item];
        }, $out);
    }

    #[DataProvider('getChangeableToPrimaryRolesWhenCannotChangeFrom')]
    public function testGetChangeableToPrimaryRolesWhenCannotChangeFrom(array $abilities)
    {
        $data = [
            'source_role_1' => $abilities,

        ];
        $userAuth = new UserAuthority($data);

        $expected = [];
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'source_role_1'));
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'not_set_role'));
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'role_3'));
        $this->assertSame($expected, $userAuth->getChangeableToPrimaryRoles('source_role_1', 'role_4'));
    }

    static public function getChangeableToPrimaryRolesWhenCannotChangeTo(): array
    {
        $from = UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE;
        $to = UserAuthority::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO;

        $out = [
            [
                $from => ['role_2'],
                $to => [],
            ],
            [
                $from => [],
                $to => [],
            ],
            [
                $from => ['role_2'],
            ],
        ];

        return array_map(function ($item) {
            return [$item];
        }, $out);
    }

    #[DataProvider('getChangeableToPrimaryRolesWhenCannotChangeTo')]
    public function testGetChangeableToPrimaryRolesWhenCannotChangeTo(array $abilities)
    {
        $data = [
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
