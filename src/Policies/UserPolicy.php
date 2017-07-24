<?php

namespace UnstoppableCarl\Arbiter\Policies;

use UnstoppableCarl\Arbiter\Policies\Concerns\OverrideWhenSelf;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole as User;

class UserPolicy
{
    use OverrideWhenSelf;

    /**
     * @var UserAuthorityContract
     */
    protected $userAbilities;

    /**
     * @var array
     */
    protected $overrideWhenSelf = [];

    /**
     * UserPolicy constructor.
     * @param UserAuthorityContract $primaryRoleUserAbilities
     * @param array|null $overrideWhenSelf
     */
    public function __construct(UserAuthorityContract $primaryRoleUserAbilities, array $overrideWhenSelf = null)
    {
        $this->userAbilities = $primaryRoleUserAbilities;
        if ($overrideWhenSelf !== null) {
            $this->overrideWhenSelf = $overrideWhenSelf;
        }
    }

    /**
     * Check if $source can view $target.
     * If $target is null, checks if there is ANY $target that this method would return true for.
     * @param User $source
     * @param string|User $target primary role name or User to get primary role from
     * @return bool
     */
    public function view(User $source, $target = null)
    {
        $source = $this->toPrimaryRole($source);
        $target = $this->toPrimaryRole($target);

        return $this->userAbilities->canView($source, $target);
    }

    /**
     * Check if $source can create a User with $primaryRole.
     * If $primaryRole is null, checks if there is ANY $primaryRole that this method would return true for.
     * @param User $source
     * @param string|null $primaryRole
     * @return bool
     */
    public function create(User $source, $primaryRole = null)
    {
        $source = $this->toPrimaryRole($source);

        return $this->userAbilities->canCreate($source, $primaryRole);
    }

    /**
     * Check if $source can update $target
     * If $target is null, checks if there is ANY $target that this method would return true for.
     * @param User $source
     * @param string|User $target primary role name or User to get primary role from
     * @return bool
     */
    public function update(User $source, $target = null)
    {
        $source = $this->toPrimaryRole($source);
        $target = $this->toPrimaryRole($target);

        return $this->userAbilities->canUpdate($source, $target);
    }

    /**
     * Check if $source can delete $target
     * If $target is null, checks if there is ANY $target that this method would return true for.
     * @param User $source
     * @param string|User $target primary role name or User to get primary role from
     * @return bool
     */
    public function delete(User $source, $target = null)
    {
        $source = $this->toPrimaryRole($source);
        $target = $this->toPrimaryRole($target);

        return $this->userAbilities->canDelete($source, $target);
    }

    /**
     * Check if $source can change the primary role of $target to $primaryRole.
     * @param User $source
     * @param string|User $target primary role name or User to get primary role from
     * @param string $primaryRole primary role to change to
     * @return bool
     */
    public function changePrimaryRole(User $source, $target, $primaryRole)
    {
        return (
            $this->changePrimaryRoleFrom($source, $target) &&
            $this->changePrimaryRoleTo($source, $primaryRole)
        );
    }

    /**
     * Check if $user can change the primary role of a user with $primaryRole.
     * If $primaryRole is null, checks if there is ANY $primaryRole that this method would return true for.
     * @param User $source
     * @param string|User|null $target primary role name or User to get primary role from
     * @return bool
     */
    public function changePrimaryRoleFrom(User $source, $target = null)
    {
        $source = $this->toPrimaryRole($source);
        $target = $this->toPrimaryRole($target);

        return $this->userAbilities->canChangePrimaryRoleFrom($source, $target);
    }

    /**
     * Check if $user can change the primary role of a user to $primaryRole.
     * If $primaryRole is null, checks if there is ANY $primaryRole that this method would return true for.
     * @param User $source
     * @param string|User|null $target primary role name or User to get primary role from
     * @return bool
     */
    public function changePrimaryRoleTo(User $source, $target = null)
    {
        $source = $this->toPrimaryRole($source);
        $target = $this->toPrimaryRole($target);

        return $this->userAbilities->canChangePrimaryRoleTo($source, $target);
    }

    /**
     * Get primary roles that a user must have to be viewed by $source.
     * @param string|User $source primary role name or User to get primary role from
     * @return array
     */
    public function getViewablePrimaryRoles($source)
    {
        $source = $this->toPrimaryRole($source);

        return $this->userAbilities->getViewablePrimaryRoles($source);
    }

    /**
     * Get primary roles that $source can set when creating a User.
     * Helper for populating primary role dropdown when creating users.
     * @param string|User $source primary role name or User to get primary role from
     * @return array
     */
    public function getCreatablePrimaryRoles($source)
    {
        $source = $this->toPrimaryRole($source);

        return $this->userAbilities->getCreatablePrimaryRoles($source);
    }

    /**
     * Get primary roles that $source can change the primary role of.
     * @param string|User $source primary role name or User to get primary role from
     * @return array
     */
    public function getUpdatableFromPrimaryRoles($source)
    {
        $source = $this->toPrimaryRole($source);

        return $this->userAbilities->getUpdatableFromPrimaryRoles($source);
    }

    /**x
     * Get primary roles that $source can change on $target.
     * If $target is null, gets all primary roles $source can potentially update a $target to.
     * Helper for populating a primary role dropdown when updating a user.
     * @param string|User $source primary role name or User to get primary role from
     * @param string|User|null $target primary role name or User to get primary role from
     * @return array
     */
    public function getUpdatableToPrimaryRoles($source, $target = null)
    {
        $source = $this->toPrimaryRole($source);
        $target = $this->toPrimaryRole($target);

        return $this->userAbilities->getUpdatableToPrimaryRoles($source, $target);
    }

    /**
     * Get primary roles that a user must have to be deleted by $source.
     * @param string|User $source primary role name or User to get primary role from
     * @return array
     */
    public function getDeletablePrimaryRoles($source)
    {
        $source = $this->toPrimaryRole($source);

        return $this->userAbilities->getDeletablePrimaryRoles($source);
    }

    /**
     * Get primary roles
     * @return array
     */
    public function getPrimaryRoles()
    {
        return $this->userAbilities->getPrimaryRoles();
    }

    /**
     * Normalize potential User value to primary role name.
     * @param string|User $value primary role name or User to get primary role from
     * @return string
     */
    protected function toPrimaryRole($value)
    {
        if ($value instanceof User) {
            return $value->getPrimaryRoleName();
        }

        return $value;
    }
}
