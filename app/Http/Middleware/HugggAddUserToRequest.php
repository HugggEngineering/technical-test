<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Log;
use App\User;

// TODO: make this middleware use the redis cache for the user via sugar-cube

class HugggAddUserToRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (static::$testUser)
        {
            $request->setUserResolver(function () {
                // stripe_id is used by UserController.addCard; could arguably be requeried from there if needed
                return static::$testUser;
            });

            Log::debug('HugggAddUserToRequest: user resolver added for test user');
        }
        else if (isset($request->hugggSession) && isset($request->hugggSession->userId)) {
            $userId = $request->hugggSession->userId;

            // This call sets the function that is run when you call $request->user
            // and is a means of passing the user on to the request that survives
            // the conversion of $request into its derived types.
            $request->setUserResolver(function () use ($userId) {
                // stripe_id is used by UserController.addCard; could arguably be requeried from there if needed
                return User::where('id', $userId)->first()->makeVisible('stripe_id');
            });

            Log::debug('HugggAddUserToRequest: user resolver added');
        }

        return $next($request);
    }

    private static $testUser;

    public static function setUserForTest(?User $user)
    {
        static::$testUser = $user;
    }
}
