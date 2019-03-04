<?php
return [
    'host' => env('REDIS_HOST', 'redis'),
    'cache_time' => (int)env('REDIS_CACHE_TIME', 1800),
];
