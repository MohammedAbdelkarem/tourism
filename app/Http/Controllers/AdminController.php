<?php
namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Traits\ResponseTrait;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    use ResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['login', 'register']]);
    }
    public function login(AdminLoginRequest $request)
    {
        if (! $token = auth()->attempt($request->only('email' , 'password')))
        {
            return $this->SendResponse(response::HTTP_UNAUTHORIZED , 'wrong email or password');
        }
        return $this->SendResponse(response::HTTP_OK , 'logged in successfully' ,['token' => $token]);
    }
    public function logout()
    {
        auth()->logout();
        
        return $this->SendResponse(response::HTTP_OK , 'logged out successfully');
    }
    
}