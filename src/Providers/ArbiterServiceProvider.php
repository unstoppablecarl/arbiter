<?php

namespace UnstoppableCarl\Arbiter\Providers;

use Illuminate\Support\ServiceProvider;
use UnstoppableCarl\Arbiter\Providers\Concerns\HandlesArbiterBindings;
use UnstoppableCarl\Arbiter\TargetSelfOverrides;
use UnstoppableCarl\Arbiter\UserAuthority;

class ArbiterServiceProvider extends ServiceProvider
{
    use HandlesArbiterBindings;

    /**
     * @var string
     */
    protected $targetSelfOverridesClass = TargetSelfOverrides::class;

    /**
     * @var string
     */
    protected $userAuthorityClass = UserAuthority::class;
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
        $configKey = basename($this->configFile, '.php');
        $this->mergeConfigFrom($this->configFile, $configKey);

        $this->registerUserAuthority(
            $this->userAuthorityClass()
        );

        $this->registerTargetSelfOverrides(
            $this->targetSelfOverridesClass()
        );
    }

    protected function userAuthorityClass()
    {
        return $this->config('user_authority.implementation_class', $this->userAuthorityClass);
    }

    protected function userAuthorityPrimaryRoleAbilities()
    {
        return $this->config('user_authority.primary_role_abilities', []);
    }

    protected function targetSelfOverridesClass()
    {
        return $this->config('target_self_overrides.implementation_class', $this->targetSelfOverridesClass);
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
