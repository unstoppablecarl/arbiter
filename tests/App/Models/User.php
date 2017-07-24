<?php

namespace UnstoppableCarl\Arbiter\Tests\App\Models;

use Illuminate\Support\Fluent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole;

class User extends GenericUser implements UserWithPrimaryRole, AuthenticatableContract
{
    use Authenticatable;

    protected function getKeyName()
    {
        return 'id';
    }

    public function getPrimaryRoleName()
    {
        return $this->primaryRoleName;
    }
}
