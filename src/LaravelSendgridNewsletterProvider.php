<?php

namespace Cierra\LaravelSendgridNewsletter;

use Illuminate\Support\ServiceProvider;
use Cierra\LaravelSendgridNewsletter\SendgridNewsletter;

class LaravelSendgridNewsletterProvider extends ServiceProvider
{

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function register()
    {
        $this->app->bind(\SendGrid::class, function () {
            return new \SendGrid(config('sendgrid-newsletter.sendgrid.api_key'));
        });
    }
    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // singleton constructor class private, get Instance function
        $this->app->singleton('sendgrid-newsletter', function(){
            return new SendgridNewsletter();
        });

        $this->publishes([
            __DIR__.'/config/sendgrid-newsletter.php' => config_path('sendgrid-newsletter.php'),
        ], 'sendgrid-newsletter');

        // add migration

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }
}