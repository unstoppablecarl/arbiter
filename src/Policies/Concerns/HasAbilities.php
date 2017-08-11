<?php

namespace UnstoppableCarl\Arbiter\Policies\Concerns;

use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole as User;

/**
 * Trait HasAbilities
 * @package UnstoppableCarl\Arbiter\Policies\Concerns
 * @property UserAuthorityContract $authority
 */
trait HasAbilities
{
    /**
     * @param mixed $value
     * @return string
     */
    abstract protected function toPrimaryRole($value);

    /**
     * @return UserAuthorityContract
     */
    abstract protected function userAuthority();

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

        return $this->userAuthority()->canView($source, $target);
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

        return $this->userAuthority()->canCreate($source, $primaryRole);
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

        return $this->userAuthority()->canUpdate($source, $target);
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

        return $this->userAuthority()->canDelete($source, $target);
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
        $source = $this->toPrimaryRole($source);
        $target = $this->toPrimaryRole($target);

        return $this->userAuthority()->canChangePrimaryRole($source, $target, $primaryRole);
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

        return $this->userAuthority()->canChangePrimaryRoleFrom($source, $target);
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

        return $this->userAuthority()->canChangePrimaryRoleTo($source, $target);
    }
}
