<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Voucher;

class VoucherServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //  $this->app->singleton('voucher', function ($app) {

        //     // $this->registerSender();
        //     // $sms = new SMS($app['sms.sender']);
        //     // $this->setSMSDependencies($sms, $app);

        //     // //Set the from setting
        //     // if ($app['config']->has('sms.from')) {
        //     //     $sms->alwaysFrom($app['config']['sms']['from']);
        //     // }

        //     return new VoucherManager();
        // });
    }

    public function provides()
    {
         // return ['App\Services\Voucher\VoucherInterface'];
    }
}
