<?php

namespace RodrigoPedra\LaravelOptimus;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Optimus\Optimus;
use RodrigoPedra\LaravelOptimus\Console\Commands\GenerateOptimusKeys;

class LaravelOptimusServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config.php' => $this->app->configPath('optimus.php')], 'config');

            $this->commands([
                GenerateOptimusKeys::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'optimus');

        $this->app->singleton(Optimus::class, function (Container $container) {
            $config = $container->make(Repository::class);

            $prime = $config->get('optimus.prime');
            $inverse = $config->get('optimus.inverse');
            $random = $config->get('optimus.random');

            return new Optimus($prime, $inverse, $random);
        });

        $this->app->alias(Optimus::class, 'optimus');
    }

    public function provides()
    {
        return [
            Optimus::class,
            'optimus',
        ];
    }
}
