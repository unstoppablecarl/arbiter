<?php

namespace UnstoppableCarl\Arbiter;

use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;

/**
 * Manages the permissions related to users
 * viewing/creating/updating/deleting/changing primary roles of other users
 * @see UserAuthorityContract for function docblocks
 * Class UserAuthority
 * @package PrimedPermissions\Services
 */
class UserAuthority implements UserAuthorityContract
{

    const CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO                = 'CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO';
    const CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE = 'CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE';

    const CAN_VIEW_USERS_WITH_PRIMARY_ROLE   = 'CAN_VIEW_USERS_WITH_PRIMARY_ROLE';
    const CAN_CREATE_USERS_WITH_PRIMARY_ROLE = 'CAN_CREATE_USERS_WITH_PRIMARY_ROLE';
    const CAN_UPDATE_USERS_WITH_PRIMARY_ROLE = 'CAN_UPDATE_USERS_WITH_PRIMARY_ROLE';
    const CAN_DELETE_USERS_WITH_PRIMARY_ROLE = 'CAN_DELETE_USERS_WITH_PRIMARY_ROLE';

    /**
     * @var array
     */
    protected $data;

    /**
     * UserAuthority constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
        /*
         * Example:

        $data = [
            // primary role name
            'my_primary_role' => [
                'permissions' => [
                    // ability with list of primary roles that it can be performed on
                    static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO                 => ['admin', 'manager'],
                    static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE  => ['admin', 'manager'],
                    static::CAN_VIEW_USERS_WITH_PRIMARY_ROLE                    => ['my_primary_role', 'admin'],
                    static::CAN_CREATE_USERS_WITH_PRIMARY_ROLE                  => ['my_primary_role', 'admin'],
                    static::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE                  => ['admin', 'manager'],
                    static::CAN_DELETE_USERS_WITH_PRIMARY_ROLE                  => ['manager'],
                ],
            ],
            'admin'           => [
                'permissions' => [
                    static::CAN_VIEW_USERS_WITH_PRIMARY_ROLE                    => ['admin', 'manager'],
                    static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO                 => ['admin', 'manager'],
                    static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE  => ['manager'],
                    static::CAN_CREATE_USERS_WITH_PRIMARY_ROLE                  => ['manager'],
                    static::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE                  => ['manager'],
                    static::CAN_DELETE_USERS_WITH_PRIMARY_ROLE                  => ['manager'],
                ],
            ],
            'manager'         => [
                'permissions' => [
                    static::CAN_VIEW_USERS_WITH_PRIMARY_ROLE => ['manager'],
                ],
            ],
        ];

        */
    }

    public function getPrimaryRoles()
    {
        return array_keys($this->data);
    }

    public function get($source, $ability)
    {
        if (isset($this->data[$source]['permissions'][$ability])) {
            return $this->data[$source]['permissions'][$ability];
        }
        return [];
    }

    public function can($source, $ability, $target)
    {
        $validTargetRoles = $this->get($source, $ability);
        return in_array($target, $validTargetRoles);
    }

    public function canAny($source, $ability)
    {
        return (bool)$this->get($source, $ability);
    }

    public function canOrAny($source, $ability, $target = null)
    {
        if ($target) {
            return $this->can($source, $ability, $target);
        }

        return $this->canAny($source, $ability);
    }

    public function canCreate($source, $target = null)
    {
        $ability = static::CAN_CREATE_USERS_WITH_PRIMARY_ROLE;

        return $this->canOrAny($source, $ability, $target);
    }

    public function canView($source, $target = null)
    {
        $ability = static::CAN_VIEW_USERS_WITH_PRIMARY_ROLE;

        return $this->canOrAny($source, $ability, $target);
    }

    public function canUpdate($source, $target = null)
    {
        $ability = static::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE;

        return $this->canOrAny($source, $ability, $target);
    }

    public function canDelete($source, $target = null)
    {
        $ability = static::CAN_DELETE_USERS_WITH_PRIMARY_ROLE;

        return $this->canOrAny($source, $ability, $target);
    }

    public function canChangePrimaryRoleFrom($source, $target = null)
    {
        $ability = static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE;
        return $this->canOrAny($source, $ability, $target);
    }

    public function canChangePrimaryRoleTo($source, $target = null)
    {
        $ability = static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO;
        return $this->canOrAny($source, $ability, $target);
    }

    public function getViewablePrimaryRoles($source)
    {
        $ability = static::CAN_VIEW_USERS_WITH_PRIMARY_ROLE;
        return $this->get($source, $ability);
    }

    public function getCreatablePrimaryRoles($source)
    {
        $ability = static::CAN_CREATE_USERS_WITH_PRIMARY_ROLE;
        return $this->get($source, $ability);
    }

    public function getUpdatableFromPrimaryRoles($source)
    {
        $ability = static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE;
        return $this->get($source, $ability);
    }

    public function getUpdatableToPrimaryRoles($source, $target = null)
    {
        if ($target) {
            $ability         = static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE;
            $canChangeTarget = $this->can($source, $ability, $target);
            if (!$canChangeTarget) {
                return [];
            }
        }

        return $this->get($source, static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO);
    }

    public function getDeletablePrimaryRoles($source)
    {
        $ability = static::CAN_DELETE_USERS_WITH_PRIMARY_ROLE;
        return $this->get($source, $ability);
    }
}
