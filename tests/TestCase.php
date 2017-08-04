<?php

namespace UnstoppableCarl\Arbiter\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use PHPUnit\Framework\TestCase as BaseTestCase;
use UnstoppableCarl\Arbiter\Contracts\UserWithPrimaryRole;

class TestCase extends BaseTestCase
{
    protected function basePath($path = null)
    {
        return __DIR__ . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    protected function wrapSingleArgumentProviderData(array $out)
    {
        return array_map(function ($item) {
            return [$item];
        }, $out);
    }

    protected function mockUser($id, $primaryRole)
    {
        $interfaces = [UserWithPrimaryRole::class, Authenticatable::class];

        $mock = $this
            ->getMockBuilder($interfaces)
            ->getMock();

        $mock->method('getPrimaryRoleName')
             ->willReturn($primaryRole);

        $mock->method('getAuthIdentifier')
             ->willReturn($id);

        return $mock;
    }

    protected function mockUserWithPrimaryRole($primaryRole)
    {
        $mock = $this->createMock(UserWithPrimaryRole::class);

        $mock->method('getPrimaryRoleName')
             ->willReturn($primaryRole);
        return $mock;
    }
}
