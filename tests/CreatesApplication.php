<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Queue;

trait CreatesApplication
{

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    // protected $baseUrl = 'http://api.dev.huggg.me';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // let's not run any jobs added to the queue
        Queue::fake();

        return $app;
    }
}