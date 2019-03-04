<?php

namespace App\Api\Controllers;

use Dingo\Api\Routing\Helpers;
// use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;



class BaseController extends Controller
{
    // use DispatchesJobs, ValidatesRequests, Helpers;

    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors());
        }
    }
}

