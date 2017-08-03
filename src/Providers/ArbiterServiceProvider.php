<?php

namespace UnstoppableCarl\Arbiter\Providers;

use Illuminate\Support\ServiceProvider;
use UnstoppableCarl\Arbiter\Providers\Concerns\HandlesArbiterBindings;

class ArbiterServiceProvider extends ServiceProvider
{
    use HandlesArbiterBindings;

    /**
     * @var string
     */
    protected $configFile = __DIR__ . '/../../config/arbiter.php';

    public function boot()
    {
        $source      = realpath($this->configFile);
        $destination = config_path(basename($this->configFile));

        $this->publishes([
            $source => $destination,
        ]);
    }

    public function register()
    {
        $this->registerConfig();
        $this->registerUserAuthority();
        $this->registerTargetSelfOverrides();
    }

    protected function registerConfig()
    {
        $configKey = basename($this->configFile, '.php');
        $this->mergeConfigFrom($this->configFile, $configKey);
    }

    protected function userAuthorityPrimaryRoleAbilities()
    {
        return $this->config('user_authority.primary_role_abilities', []);
    }

    protected function targetSelfOverrides()
    {
        return $this->config('target_self_overrides.ability_overrides', []);
    }

    protected function config($key, $default = null)
    {
        return $this->app['config']->get('arbiter.' . $key, $default);
    }
}
