<?php

namespace RodrigoPedra\LaravelOptimus;

use Jenssegers\Optimus\Optimus;
use Illuminate\Support\ServiceProvider;
use RodrigoPedra\LaravelOptimus\Console\Commands\GenerateOptimusKeys;

class LaravelOptimusServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $configPath = $this->app[ 'path.config' ] . DIRECTORY_SEPARATOR . 'optimus.php';
        $this->publishes( [ __DIR__ . '/../config.php' => $configPath ], 'config' );

        if ($this->app->runningInConsole()) {
            $this->commands( [
                GenerateOptimusKeys::class,
            ] );
        }
    }

    public function register()
    {
        $this->mergeConfigFrom( __DIR__ . '/../config.php', 'optimus' );

        $this->app->singleton( Optimus::class, function ( $app ) {
            $prime   = $app[ 'config' ]->get( 'optimus.prime' );
            $inverse = $app[ 'config' ]->get( 'optimus.inverse' );
            $random  = $app[ 'config' ]->get( 'optimus.random' );

            return new Optimus( $prime, $inverse, $random );
        } );

        $this->app->alias( Optimus::class, 'optimus' );
    }

    public function provides()
    {
        return [
            Optimus::class,
            'optimus',
        ];
    }
}
