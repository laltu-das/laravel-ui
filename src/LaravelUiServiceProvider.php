<?php

namespace Laltu\LaravelUi;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Laltu\LaravelUi\Commands\InstallPresetCommand;

class LaravelUiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            InstallPresetCommand::class
        ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [InstallPresetCommand::class];
    }
}
