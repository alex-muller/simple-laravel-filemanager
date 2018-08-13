<?php


namespace Muller\Filemanager;


use Illuminate\Support\ServiceProvider;

class FMServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->routes();
        $this->publish();
        $this->views();
    }

    protected function routes()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    protected function publish()
    {
        $this->publishes([
            __DIR__.'/../public/' => public_path('vendors'),
            __DIR__.'/config/slfm.php' => config_path('slfm.php')
        ]);
    }

    protected function views()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'muller');
    }
}