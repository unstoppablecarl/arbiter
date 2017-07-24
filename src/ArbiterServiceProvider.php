<?php

namespace UnstoppableCarl\Arbiter;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Policies\UserPolicy;
use UnstoppableCarl\Arbiter\UserAuthority;
use Illuminate\Config\Repository;

class ArbiterServiceProvider extends ServiceProvider
{

    /**
     * @var string
     */
    protected $configFilePath = __DIR__ . '/config/arbiter.php';

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var string
     */
    protected $userPolicyClass = UserPolicy::class;

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
    }

    public function register()
    {
        $this->config = $this->registerConfig($this->configFilePath);

        $this->bind(UserAuthorityContract::class, function () {
            $data = $this->config->get('primary_role_abilities', []);
            return new UserAuthority($data);
        });

        $this->bind($this->userPolicyClass, function () {
            $userPolicy       = $this->userPolicyClass;
            $userAuthority    = $this->app->make(UserAuthorityContract::class);
            $overrideWhenSelf = $this->config->get('override_when_self', null);
            return new $userPolicy($userAuthority, $overrideWhenSelf);
        });
    }

    protected function registerConfig($filePath = null)
    {
        $fileName = basename($filePath);
        $key      = basename($fileName, '.php');

        $this->publishes([
            $filePath => config_path($fileName),
        ]);

        $this->mergeConfigFrom($filePath, $key);
        $config = $this->app['config']->get($key);

        return new Repository($config);
    }
}
