<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Price;
use App\Http\Middleware\HugggGetSession;
use App\Http\Middleware\HugggAddUserToRequest;

class TestHelper
{
    public static function assertArrayHasKeys($keys, $array, $context)
    {
        array_walk($keys, function ($value, $key) use ($array, $context) {
            if (is_array($value) && !is_array($array[$key])) {
                $context->fail("TestHelper::assertArrayHasKeys expected $key to be array.");
            } elseif (is_array($value)) {
                self::assertArrayHasKeys($value, $array[$key], $context);
            } else {
                $context->assertArrayHasKey($value, $array);
            }
        });
    }

    public static function createUser($phoneNumber)
    {
        return factory(User::class)->create(['type' => 0, 'phone_number' => $phoneNumber]);
    }

    public static function createPrice()
    {
        $price = factory(Price::class)->make();
        $price->save();
        return $price;
    }

    public static function sendHuggg($price, $receiver, $context)
    {
        return function ($message) use ($price, $receiver, $context) {
            //Add brand via factor
            $hugData = [
                'token'     => 'tok_visa',
                'purchase'  => $price->id,
                'receiver'  => $receiver,
                'message'   => $message,
            ];

            return $context->json('POST', 'api/v2/hugs', $hugData);
        };
    }

    public static function setupFakeSession(array $userFields, array $permissions)
    {
        $user = factory(User::class)->create($userFields);

        static::setupFakeSessionWithUser($user, $permissions);
    }

    public static function setupFakeSessionWithUser(?User $user, ?array $permissions)
    {
        if ($user === null || $permissions === null) {
            HugggAddUserToRequest::setUserForTest(null);
        }
        else {
            $session = (object)[ 'permissions' => (object)$permissions, 'userId' => $user->id, 'clientId' => 'bar' ];

            HugggAddUserToRequest::setUserForTest($user);
        }
    }
}
