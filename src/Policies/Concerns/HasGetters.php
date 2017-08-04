<?php


namespace UnstoppableCarl\Arbiter\Policies\Concerns;

use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole as User;

trait HasGetters
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
     * Get primary roles that a user must have to be viewed by $source.
     * @param string|User $source primary role name or User to get primary role from
     * @return array
     */
    public function getViewablePrimaryRoles($source)
    {
        $source = $this->toPrimaryRole($source);

        return $this->userAuthority()->getViewablePrimaryRoles($source);
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

        return $this->userAuthority()->getCreatablePrimaryRoles($source);
    }

    /**
     * Get primary roles that $source can change the primary role of.
     * @param string|User $source primary role name or User to get primary role from
     * @return array
     */
    public function getChangeableFromPrimaryRoles($source)
    {
        $source = $this->toPrimaryRole($source);

        return $this->userAuthority()->getChangeableFromPrimaryRoles($source);
    }

    /**x
     * Get primary roles that $source can change on $target.
     * If $target is null, gets all primary roles $source can potentially update a $target to.
     * Helper for populating a primary role dropdown when updating a user.
     * @param string|User $source primary role name or User to get primary role from
     * @param string|User|null $target primary role name or User to get primary role from
     * @return array
     */
    public function getChangeableToPrimaryRoles($source, $target = null)
    {
        $source = $this->toPrimaryRole($source);
        $target = $this->toPrimaryRole($target);

        return $this->userAuthority()->getChangeableToPrimaryRoles($source, $target);
    }

    /**
     * Get primary roles that a user must have to be deleted by $source.
     * @param string|User $source primary role name or User to get primary role from
     * @return array
     */
    public function getDeletablePrimaryRoles($source)
    {
        $source = $this->toPrimaryRole($source);

        return $this->userAuthority()->getDeletablePrimaryRoles($source);
    }

    /**
     * Get primary roles
     * @return array
     */
    public function getPrimaryRoles()
    {
        return $this->userAuthority()->getPrimaryRoles();
    }
}
