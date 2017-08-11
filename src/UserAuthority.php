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
    protected $data = [];

    /**
     * UserAuthority constructor.
     * @param array $primaryRoleAbilities
     */
    public function __construct(array $primaryRoleAbilities = [])
    {
        $this->parseData($primaryRoleAbilities);
        /*
         * Example:

        $data = [
            // primary role name
            'my_primary_role' => [
                // ability with list of primary roles that it can be performed on
                static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO                 => ['admin', 'manager'],
                static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE  => ['admin', 'manager'],
                static::CAN_VIEW_USERS_WITH_PRIMARY_ROLE                    => ['my_primary_role', 'admin'],
                static::CAN_CREATE_USERS_WITH_PRIMARY_ROLE                  => ['my_primary_role', 'admin'],
                static::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE                  => ['admin', 'manager'],
                static::CAN_DELETE_USERS_WITH_PRIMARY_ROLE                  => ['manager'],
            ],
            'admin'           => [
                static::CAN_VIEW_USERS_WITH_PRIMARY_ROLE                    => ['admin', 'manager'],
                static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO                 => ['admin', 'manager'],
                static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE  => ['manager'],
                static::CAN_CREATE_USERS_WITH_PRIMARY_ROLE                  => ['manager'],
                static::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE                  => ['manager'],
                static::CAN_DELETE_USERS_WITH_PRIMARY_ROLE                  => ['manager'],
            ],
            'manager'         => [
                static::CAN_VIEW_USERS_WITH_PRIMARY_ROLE => ['manager'],
            ],
        ];

        */
    }

    protected function parseData(array $data)
    {
        foreach ($data as $role => $permissions) {
            if (!$permissions) {
                $this->data[$role] = [];
                continue;
            }

            foreach ($permissions as $ability => $targets) {
                $targets = $targets ?: [];

                if (is_string($targets)) {
                    $targets = [$targets];
                }
                $this->set($role, $ability, $targets);
            }
        }
    }

    protected function set($source, $ability, array $targets = [])
    {
        $this->data[$source][$ability] = $targets;
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

    public function canView($source, $target = null)
    {
        $ability = static::CAN_VIEW_USERS_WITH_PRIMARY_ROLE;
        return $this->canOrAny($source, $ability, $target);
    }

    public function canCreate($source, $target = null)
    {
        $ability = static::CAN_CREATE_USERS_WITH_PRIMARY_ROLE;
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

    public function canChangePrimaryRole($source, $target, $newPrimaryRole)
    {
        $from = $this->canChangePrimaryRoleFrom($source, $target);
        $to   = $this->canChangePrimaryRoleTo($source, $newPrimaryRole);
        return $from && $to;
    }

    public function getPrimaryRoles()
    {
        return array_keys($this->data);
    }

    public function get($source, $ability)
    {
        if (isset($this->data[$source][$ability])) {
            return $this->data[$source][$ability];
        }
        return [];
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

    public function getUpdatablePrimaryRoles($source)
    {
        $ability = static::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE;
        return $this->get($source, $ability);
    }

    public function getDeletablePrimaryRoles($source)
    {
        $ability = static::CAN_DELETE_USERS_WITH_PRIMARY_ROLE;
        return $this->get($source, $ability);
    }

    public function getChangeableFromPrimaryRoles($source)
    {
        $ability = static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE;
        return $this->get($source, $ability);
    }

    public function getChangeableToPrimaryRoles($source, $target = null)
    {
        if ($target && !$this->canChangePrimaryRoleFrom($source, $target)) {
            return [];
        }
        return $this->get($source, static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO);
    }
}
