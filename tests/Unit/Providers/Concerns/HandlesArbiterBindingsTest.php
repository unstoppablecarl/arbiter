<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\Fluent;
use PHPUnit\Framework\Attributes\DataProvider;
use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\Tests\App\Providers\TestableArbiterServiceProvider as TestServiceProvider;
use UnstoppableCarl\Arbiter\Tests\TestCase;
use UnstoppableCarl\Arbiter\UserAuthority;

class HandlesArbiterBindingsTest extends TestCase
{
    protected function freshApp($provider = TestServiceProvider::class): Application
    {
        $testAppPath = $this->basePath();
        $app = new Application($testAppPath);
        $app->register($provider);
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

    protected function assertBound(Application $app, $contract, $concrete, $shared): void
    {
        $this->assertTrue(
            $app->bound($contract),
            $contract . ' contract is bound'
        );

        $this->assertSame($shared, $app->isShared($contract), 'is shared matches arg');

        $instance = $app->make($contract);
        $msg = 'is instance of ' . $concrete;
        $this->assertInstanceOf($concrete, $instance, $msg);
    }

    static public function defaultBindings(): array
    {
        return [
            [UserAuthorityContract::class, UserAuthority::class, 'publicRegisterUserAuthority'],
            [TargetSelfOverridesContract::class, TargetSelfOverrides::class, 'publicRegisterTargetSelfOverrides'],
        ];
    }

    #[DataProvider('defaultBindings')]
    public function testDefaultBindings($contract, $concrete, $method)
    {

        $app = $this->freshApp();
        /** @var TestServiceProvider $provider */
        $provider = $app->getProvider(TestServiceProvider::class);

        $provider->{$method}();

        $this->assertBound($app, $contract, $concrete, false);
    }

    #[DataProvider('defaultBindings')]
    public function testDefaultSharedBindings($contract, $concrete, $method)
    {
        $app = $this->freshApp();
        /** @var TestServiceProvider $provider */
        $provider = $app->getProvider(TestServiceProvider::class);

        $provider->{$method}(true);

        $this->assertBound($app, $contract, $concrete, true);
    }

    public function testUserAuthorityPrimaryRoleAbilitiesInjection()
    {
        $settings = [
            'role_1' => [
                'ability_1' => ['role_2', 'role_3'],
            ],
        ];

        $app = $this->freshApp();

        /** @var TestServiceProvider $provider */
        $provider = $app->getProvider(TestServiceProvider::class);
        $provider->publicRegisterUserAuthority(false, Fluent::class);
        $provider->setUserAuthorityPrimaryRoleAbilities($settings);

        $userAuthority = $app->make(UserAuthorityContract::class);

        $this->assertSame($settings, $userAuthority->toArray());
    }

    public function testTargetSelfOverridesInjection()
    {
        $settings = [
            'ability_1' => true,
            'ability_2' => false,
            'ability_3' => null,
        ];

        $app = $this->freshApp();

        /** @var TestServiceProvider $provider */
        $provider = $app->getProvider(TestServiceProvider::class);
        $provider->publicRegisterTargetSelfOverrides(false, Fluent::class);
        $provider->setTargetSelfOverrides($settings);

        $userAuthority = $app->make(TargetSelfOverridesContract::class);

        $this->assertSame($settings, $userAuthority->toArray());
    }
}
