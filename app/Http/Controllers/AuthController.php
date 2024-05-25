<?php

namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use App\Http\Requests\CodeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ResponseTrait;
    
    public function checkCode(CodeRequest $request)
    {
        $validatedCode = $request->validated()['code'];

        $defaultCode = Cache::get('code');

        if(!$defaultCode)
        {
            return $this->SendResponse(response::HTTP_GONE , 'expired code');
        }
        if($validatedCode == $defaultCode)
        {
            Cache::delete('code');
            return $this->SendResponse(response::HTTP_OK , 'correct code');
        }
        return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'invalid code');
    }
}
