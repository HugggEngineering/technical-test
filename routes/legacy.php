<?php

/**
 * API ROUTES
 */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [], function ($api) {
    $api->group(['prefix' => 'v2/', 'middleware' => ['hugggGetUser']], function ($api) {
        //Hugggs
        $api->resource('hugs', 'App\Api\Controllers\HugController');

        $api->get('huggg/get/{id}', 'App\Api\Controllers\HugController@show');
    });
});
