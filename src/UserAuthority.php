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
    const string CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO = 'CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO';
    const string CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE = 'CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE';

    const string CAN_VIEW_USERS_WITH_PRIMARY_ROLE = 'CAN_VIEW_USERS_WITH_PRIMARY_ROLE';
    const string CAN_CREATE_USERS_WITH_PRIMARY_ROLE = 'CAN_CREATE_USERS_WITH_PRIMARY_ROLE';
    const string CAN_UPDATE_USERS_WITH_PRIMARY_ROLE = 'CAN_UPDATE_USERS_WITH_PRIMARY_ROLE';
    const string CAN_DELETE_USERS_WITH_PRIMARY_ROLE = 'CAN_DELETE_USERS_WITH_PRIMARY_ROLE';

    protected array $data = [];

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

    protected function parseData(array $data): void
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

    protected function set($source, $ability, array $targets = []): void
    {
        $this->data[$source][$ability] = $targets;
    }

    public function can(string $source, string $ability, string $target): bool
    {
        $validTargetRoles = $this->get($source, $ability);
        return in_array($target, $validTargetRoles);
    }

    public function canAny(string $source, string $ability): bool
    {
        return (bool)$this->get($source, $ability);
    }

    public function canOrAny(string $source, string $ability, ?string $target = null): bool
    {
        if ($target) {
            return $this->can($source, $ability, $target);
        }
        return $this->canAny($source, $ability);
    }

    public function canView(string $source, ?string $target = null): bool
    {
        $ability = static::CAN_VIEW_USERS_WITH_PRIMARY_ROLE;
        return $this->canOrAny($source, $ability, $target);
    }

    public function canCreate(string $source, ?string $target = null): bool
    {
        $ability = static::CAN_CREATE_USERS_WITH_PRIMARY_ROLE;
        return $this->canOrAny($source, $ability, $target);
    }

    public function canUpdate(string $source, ?string $target = null): bool
    {
        $ability = static::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE;
        return $this->canOrAny($source, $ability, $target);
    }

    public function canDelete(string $source, ?string $target = null): bool
    {
        $ability = static::CAN_DELETE_USERS_WITH_PRIMARY_ROLE;
        return $this->canOrAny($source, $ability, $target);
    }

    public function canChangePrimaryRoleFrom(string $source, ?string $target = null): bool
    {
        $ability = static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE;
        return $this->canOrAny($source, $ability, $target);
    }

    public function canChangePrimaryRoleTo(string $source, ?string $target = null): bool
    {
        $ability = static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO;
        return $this->canOrAny($source, $ability, $target);
    }

    public function canChangePrimaryRole(string $source, string $target, string $newPrimaryRole): bool
    {
        $from = $this->canChangePrimaryRoleFrom($source, $target);
        $to = $this->canChangePrimaryRoleTo($source, $newPrimaryRole);
        return $from && $to;
    }

    public function getPrimaryRoles(): array
    {
        return array_keys($this->data);
    }

    public function get(string $source, string $ability): array
    {
        if (isset($this->data[$source][$ability])) {
            return $this->data[$source][$ability];
        }
        return [];
    }

    public function getViewablePrimaryRoles(string $source): array
    {
        $ability = static::CAN_VIEW_USERS_WITH_PRIMARY_ROLE;
        return $this->get($source, $ability);
    }

    public function getCreatablePrimaryRoles(string $source): array
    {
        $ability = static::CAN_CREATE_USERS_WITH_PRIMARY_ROLE;
        return $this->get($source, $ability);
    }

    public function getUpdatablePrimaryRoles(string $source): array
    {
        $ability = static::CAN_UPDATE_USERS_WITH_PRIMARY_ROLE;
        return $this->get($source, $ability);
    }

    public function getDeletablePrimaryRoles(string $source): array
    {
        $ability = static::CAN_DELETE_USERS_WITH_PRIMARY_ROLE;
        return $this->get($source, $ability);
    }

    public function getChangeableFromPrimaryRoles(string $source): array
    {
        $ability = static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_WITH_PRIMARY_ROLE;
        return $this->get($source, $ability);
    }

    public function getChangeableToPrimaryRoles(string $source, ?string $target = null): array
    {
        if ($target && !$this->canChangePrimaryRoleFrom($source, $target)) {
            return [];
        }
        return $this->get($source, static::CAN_CHANGE_PRIMARY_ROLE_OF_USERS_TO);
    }
}
