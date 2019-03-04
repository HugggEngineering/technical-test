<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait UuidTrait
{
    public function getIncrementing()
    {
        return false;
    }

    public static function bootUuidTrait()
    {
        static::creating(function ($model) {
            $model->incrementing = false;
            $model->{$model->getKeyName()} = (string)Uuid::uuid4();
        });
    }
}
