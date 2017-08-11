<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit\Providers;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Policies\UserPolicy;
use UnstoppableCarl\Arbiter\Providers\ArbiterServiceProvider;
use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\Tests\TestCase;

/**
 * @covers \UnstoppableCarl\Arbiter\Providers\ArbiterServiceProvider
 */
class ArbiterServiceProviderTest extends TestCase
{
    protected function freshApp(array $config = [])
    {
        $testAppPath = $this->basePath();
        $app         = new Application($testAppPath);
        $app->instance('config', new Repository($config));
        $app->register(ArbiterServiceProvider::class);
        $app->boot();
        return $app;
    }

    public function testConfig()
    {
        $app       = $this->freshApp();
        $toPublish = ArbiterServiceProvider::pathsToPublish(ArbiterServiceProvider::class);
        $source    = realpath($this->basePath('../config/arbiter.php'));
        $this->assertArrayHasKey($source, $toPublish);
    }

    public function testBindings()
    {
        $app = $this->freshApp();

        $expected = UserAuthorityContract::class;
        $actual   = $app->make(UserAuthorityContract::class);
        $this->assertInstanceOf($expected, $actual);

        $expected = UserPolicy::class;
        $actual   = $app->make(UserPolicy::class);
        $this->assertInstanceOf($expected, $actual);


        $expected = TargetSelfOverrides::class;
        $actual   = $app->make(TargetSelfOverridesContract::class);
        $this->assertInstanceOf($expected, $actual);
    }
}
