<?php

namespace App\Providers;

use App\Interfaces\INotificationService;
use App\Notifications\NotificationChannelProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(INotificationService::class, function(Application $app) {
            $channels = [];
            foreach ( NotificationChannelProvider::getChannels() as $channel) {
                $channels[] = $app->make($channel);
            }
            return $channels;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict();
    }
}
