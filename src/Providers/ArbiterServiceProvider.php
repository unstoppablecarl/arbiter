<?php

namespace UnstoppableCarl\Arbiter\Providers;

use Illuminate\Support\ServiceProvider;
use UnstoppableCarl\Arbiter\Providers\Concerns\HandlesArbiterBindings;

class ArbiterServiceProvider extends ServiceProvider
{
    use HandlesArbiterBindings;

    protected string $configFile = __DIR__ . '/../../config/arbiter.php';

    public function boot(): void
    {
        $source = realpath($this->configFile);
        $destination = config_path(basename($this->configFile));

        $this->publishes([
            $source => $destination,
        ]);
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerUserAuthority();
        $this->registerTargetSelfOverrides();
    }

    protected function registerConfig(): void
    {
        $configKey = basename($this->configFile, '.php');
        $this->mergeConfigFrom($this->configFile, $configKey);
    }

    protected function userAuthorityPrimaryRoleAbilities(): array
    {
        return $this->config('user_authority.primary_role_abilities', []);
    }

    protected function targetSelfOverrides(): array
    {
        return $this->config('target_self_overrides.ability_overrides', []);
    }

    protected function config(string $key, $default = null): mixed
    {
        return $this->app['config']->get('arbiter.' . $key, $default);
    }
}
