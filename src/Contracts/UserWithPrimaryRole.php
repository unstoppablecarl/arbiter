<?php

namespace UnstoppableCarl\Arbiter\Contracts;

/**
 * Each user has exactly 1 primary role with a unique name
 * Interface UserHasPrimaryRoleContract
 */
interface UserWithPrimaryRole
{

    /**
     * Get the Primary Role of this user.
     * @return string
     */
    public function getPrimaryRoleName();
}
