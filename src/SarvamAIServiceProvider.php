<?php

namespace NextBuild\SarvamAI;

use Illuminate\Support\ServiceProvider;

class SarvamAIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sarvam-ai', function ($app) {
            return new SarvamAI();
        });

        $this->app->alias('sarvam-ai', SarvamAI::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/sarvam-ai.php' => config_path('sarvam-ai.php'),
        ], 'sarvam-ai-config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/sarvam-ai.php', 'sarvam-ai'
        );
    }
}