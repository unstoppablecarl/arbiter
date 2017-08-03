<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit\Providers;

use Illuminate\Foundation\Application;
use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\Tests\App\Providers\TestableArbiterServiceProvider as TestServiceProvider;
use UnstoppableCarl\Arbiter\Tests\TestCase;
use UnstoppableCarl\Arbiter\UserAuthority;

class HandlesArbiterBindingsTest extends TestCase
{
    protected function freshApp()
    {
        $testAppPath = $this->basePath();
        $app         = new Application($testAppPath);
        $app->register(TestServiceProvider::class);
        $app->boot();
        return $app;
    }

    public function testUserAuthority()
    {
        $app = $this->freshApp();
        /** @var TestServiceProvider $provider */
        $provider = $app->getProvider(TestServiceProvider::class);

        $this->assertInstanceOf(TestServiceProvider::class, $provider);
    }

    protected function assertBound(Application $app, $contract, $concrete, $shared)
    {
        $this->assertTrue(
            $app->bound($contract)
        );

        $this->assertSame($shared, $app->isShared($contract));

        $instance = $app->make($contract);
        $this->assertInstanceOf($concrete, $instance);
    }

    public function defaultBindings()
    {
        return [
            [UserAuthorityContract::class, UserAuthority::class, 'public_registerUserAuthority'],
            [TargetSelfOverridesContract::class, TargetSelfOverrides::class, 'public_registerTargetSelfOverrides'],
        ];
    }

    /**
     * @dataProvider defaultBindings()
     */
    public function testDefaultBindings($contract, $concrete, $method)
    {
        $app = $this->freshApp();
        /** @var TestServiceProvider $provider */
        $provider = $app->getProvider(TestServiceProvider::class);

        $provider->{$method}();

        $this->assertBound($app, $contract, $concrete, false);
    }

    /**
     * @dataProvider defaultBindings()
     */
    public function testDefaultSharedBindings($contract, $concrete, $method)
    {
        $app = $this->freshApp();
        /** @var TestServiceProvider $provider */
        $provider = $app->getProvider(TestServiceProvider::class);

        $provider->{$method}(true);

        $this->assertBound($app, $contract, $concrete, true);
    }

}
