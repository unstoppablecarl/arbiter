<?php

namespace UnstoppableCarl\Arbiter;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use UnstoppableCarl\Arbiter\Contracts\UserAuthorityContract;
use UnstoppableCarl\Arbiter\Policies\UserPolicy;

class ArbiterServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $configFilePath = __DIR__ . '/config/arbiter.php';

    /**
     * @var array
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
            $data = Arr::get($this->config, 'primary_role_abilities', []);
            return new UserAuthority($data);
        });

        $this->bind($this->userPolicyClass, function () {
            $userPolicy       = $this->userPolicyClass;
            $userAuthority    = $this->app->make(UserAuthorityContract::class);
            $overrideWhenSelf = Arr::get($this->config, 'override_when_self', null);
            return new $userPolicy($userAuthority, $overrideWhenSelf);
        });
    }

    protected function registerConfig($filePath = null)
    {
        $fileName   = basename($filePath);
        $key        = basename($fileName, '.php');
        $configPath = $this->app->make('path.config');

        $this->publishes([
            $filePath => $configPath . '/' . $fileName,
        ]);

        $this->mergeConfigFrom($filePath, $key);
        return $this->app['config']->get($key);
    }
}
