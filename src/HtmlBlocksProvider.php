<?php

namespace Dyusha\HtmlEditor;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class HtmlBlocksProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->bootBladeDirective();

        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('html-editor.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'html-editor');

        $this->publishes([
            __DIR__ . '/migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/assets/js' => config('html-editor.paths.js', base_path('resources/assets/js/components')),
            __DIR__ . '/assets/sass' => config('html-editor.paths.sass', base_path('resources/assets/sass/plugins')),
        ], 'assets');

        $this->loadViewsFrom(__DIR__ . '/views', 'html-editor');

        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views/vendor/html-editor')
        ], 'views');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function bootBladeDirective()
    {
        Blade::directive('block', function ($expression) {
            return "<?php if (! Dyusha\HtmlEditor\HtmlBlocks::setUp{$expression}) { ?>";
        });

        Blade::directive('endblock', function () {
            return "<?php } echo Dyusha\HtmlEditor\HtmlBlocks::tearDown() ?>";
        });
    }
}
