<?php

namespace Adithyan\ActivityLogVisualizer;

use Illuminate\Support\ServiceProvider;

class ActivityLogVisualizerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'activitylog-visualizer');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/activitylog-visualizer'),
            ], 'views');

            $this->publishes([
                __DIR__ . '/../config/activitylog-visualizer.php' => config_path('activitylog-visualizer.php'),
            ], 'config');
        }
    }

    public function register()
    {
        // merge default config 
        // $this->mergeConfigFrom(__DIR__.'/../config/activitylog-visualizer.php', 'activitylog-visualizer');
    }
}
