<?php

namespace Adithyan\ActivityLogVisualizer;

use Illuminate\Support\ServiceProvider;

class ActivityLogVisualizerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes and views
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'activitylog-visualizer');

        if ($this->app->runningInConsole()) {

            // Publish config
            $this->publishes([
                __DIR__ . '/../config/activitylog-visualizer.php' => config_path('activitylog-visualizer.php'),
            ], 'config');

            // Publish views
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/activitylog-visualizer'),
            ], 'views');

            // Publish assets: CSS, JS, fonts
            $this->publishes([
                __DIR__ . '/../resources/css' => public_path('vendor/activitylog-visualizer/css'),
                __DIR__ . '/../resources/js' => public_path('vendor/activitylog-visualizer/js'),
                __DIR__ . '/../resources/fonts' => public_path('vendor/activitylog-visualizer/fonts'),
            ], 'assets');

            // Development symlink (optional)
            if ($this->app->environment('local', 'testing')) {
                $packagePath = __DIR__ . '/../resources';
                $publicPath = public_path('vendor/activitylog-visualizer');

                if (!file_exists($publicPath)) {
                    $this->app['files']->makeDirectory($publicPath, 0755, true);
                    $this->app['files']->link($packagePath . '/css', $publicPath . '/css');
                    $this->app['files']->link($packagePath . '/js', $publicPath . '/js');
                    $this->app['files']->link($packagePath . '/fonts', $publicPath . '/fonts');
                }
            }
        }
    }

    public function register()
    {
        // Optionally merge default config
        // $this->mergeConfigFrom(__DIR__.'/../config/activitylog-visualizer.php', 'activitylog-visualizer');
    }
}
