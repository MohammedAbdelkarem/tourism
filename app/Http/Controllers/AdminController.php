<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Event\SendEmailEvent;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\AdminLoginRequest;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    use ResponseTrait;
    public function __construct()
    {
        // $this->middleware('auth:admin', ['except' => ['login', 'register' , 'sendCode']]);
    }
    public function login(AdminLoginRequest $request)
    {
        if (! $token = auth()->attempt($request->only('email' , 'password')))
        {
            return $this->SendResponse(response::HTTP_UNAUTHORIZED , 'wrong email or password');
        }

        Cache::forever('admin_email' , $request->email);

        return $this->SendResponse(response::HTTP_OK , 'logged in successfully' ,['token' => $token]);
    }
    public function logout()
    {
        auth()->logout();
        
        return $this->SendResponse(response::HTTP_OK , 'logged out successfully');
    }
    public function sendCode()
    {
        $email = /*Cache::get('admin_email')*/'mayagritaabouasali@gmail.com';

        $code = RandomCode();

        Cache::put('code', $code , now()->addHour());

        event(new SendEmailEvent($email , $code));

        return $this->SendResponse(response::HTTP_OK , 'email sended successfully' , ['code' => $code]);
    }

    public function resetPassword(PasswordRequest $request)
    {
        $password = $request->validated()['password'];

        hashing_password($password);

        Admin::adminEmail()->update([
            'password' => $password
        ]);

        return $this->SendResponse(response::HTTP_OK , 'password updated successfully');
    }
}