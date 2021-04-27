<?php

namespace Anemaloy\GetMKADDistance;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use Anemaloy\GetMKADDistance\GetYandexDistanceToMKAD;

class GetMKADDistanceServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     *  Bootstrap the application events.
     *
     */
    public function boot()
    {
        $config = realpath(__DIR__ . '/../config/mkad_distance.php');

        $this->publishes([
            $config => config_path('mkad_distance.php'),
        ], 'config');

        $this->mergeConfigFrom($config, 'mkad_distance');
    }

    /**
     * Register the service provider.
     *
     */
    public function register()
    {
        $this->app->singleton('GetMKADDistance', function (Container $app) {
            $config = $app['config'];
            return new GetYandexDistanceToMKAD($config);
        });
    }

    public function provides()
    {
        return [
            'GetMKADDistance',
        ];
    }
}
