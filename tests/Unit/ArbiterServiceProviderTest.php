<?php

namespace UnstoppableCarl\Arbiter\Tests\Unit;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as RepositoryContract;
use Illuminate\Foundation\Application;
use UnstoppableCarl\Arbiter\Contracts\TargetSelfOverridesContract;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Policies\UserPolicy;
use UnstoppableCarl\Arbiter\Providers\ArbiterServiceProvider;
use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\Tests\TestCase;

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
//
//    public function testUserAuthority()
//    {
//        $config = [
//            'arbiter' => [
//                'primary_role_abilities' => [
//                    'primary_role_1' => [
//                        'permissions' => [
//                            'ability_true'  => ['result_1'],
//                            'ability_false' => ['result_2'],
//                        ],
//                    ],
//                    'primary_role_2' => [
//                        'permissions' => [
//                            'ability2_true'  => ['result_3'],
//                            'ability2_false' => ['result_4'],
//                        ],
//                    ],
//
//                ],
//            ],
//        ];
//
//        $app = $this->freshApp($config);
//
//        /** @var UserAuthorityContract $userAuthority */
//        $userAuthority = $app->make(UserAuthorityContract::class);
//
//        $expected = ['primary_role_1', 'primary_role_2'];
//        $actual   = $userAuthority->getPrimaryRoles();
//        $msg      = 'sets UserAuthority primary roles via config';
//        $this->assertEquals($expected, $actual, $msg);
//
//        foreach ($config['arbiter']['primary_role_abilities'] as $primaryRole => $row) {
//            $permissions = $row['permissions'];
//            foreach ($permissions as $ability => $targets) {
//                $actual   = $userAuthority->get($primaryRole, $ability);
//                $expected = $targets;
//                $msg      = 'sets UserAuthority data via config';
//                $this->assertEquals($expected, $actual, $msg);
//            }
//        }
//    }
//

    public function testUserPolicy()
    {
        $config = [
            'arbiter' => [
                'override_when_self'     => [
                    'view'   => true,
                    'delete' => false,
                ],
                'primary_role_abilities' => [
                    'primary_role_1' => [
                        'permissions' => [
                            'ability_true'  => ['result_1'],
                            'ability_false' => ['result_2'],
                        ],
                    ],
                    'primary_role_2' => [
                        'permissions' => [
                            'ability2_true'  => ['result_3'],
                            'ability2_false' => ['result_4'],
                        ],
                    ],

                ],
            ],
        ];

        $app = $this->freshApp($config);
        /** @var UserAuthorityContract $userAuthority */
        $userAuthority = $app->make(UserAuthorityContract::class);
        /** @var UserPolicy $policy */
        $policy = $app->make(UserPolicy::class);

        $msg      = 'injects UserAuthorityContract';
        $expected = $userAuthority->getPrimaryRoles();
        $actual   = $policy->getPrimaryRoles();
        $this->assertEquals($expected, $actual, $msg);
    }
}
