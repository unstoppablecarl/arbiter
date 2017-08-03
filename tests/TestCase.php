<?php

namespace UnstoppableCarl\Arbiter\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use UnstoppableCarl\Arbiter\Tests\App\Models\User;

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

    protected function freshUser($id, $primaryRole)
    {
        return new User([
            'id'              => $id,
            'primaryRoleName' => $primaryRole,
        ]);
    }
}
