<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Authenticate the user's personal channel...
         */
        // Broadcast::channel('App.User.*', function ($user, $userId) {
        //     return (int) $user->id === (int) $userId;
        // });

        Broadcast::channel('huggg.*', function ($user, $userId) {
            if ($user->id == $userId) { // Replace with real ACL
                return true;
            }
        });
    }
}