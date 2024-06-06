<?php
namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use App\Event\SendEmailEvent;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Admin\EmailRequest;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordRequest;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    use ResponseTrait;
    public function login(LoginRequest $request)
    {
        if (! $token = auth()->attempt($request->only('name' , 'password')))
        {
            return $this->SendResponse(response::HTTP_UNAUTHORIZED , 'wrong name or password');
        }

        Cache::forever('admin_name' , $request->name);

        return $this->SendResponse(response::HTTP_OK , 'logged in successfully' ,['token' => $token]);
    }

    public function storeEmail(EmailRequest $request)
    {
        $email = $request->validated()['email'];

        Admin::adminName()->update([
            'email' => $email
        ]);

        return $this->SendResponse(response::HTTP_OK , 'admin email added with success');
    }
    public function logout()
    {
        auth()->logout();
        
        return $this->SendResponse(response::HTTP_OK , 'logged out successfully');
    }
    public function sendCode()
    {
        $email = Cache::get('admin_name');

        $code = RandomCode();

        Cache::put('code', $code , now()->addHour());

        event(new SendEmailEvent($email , $code));

        return $this->SendResponse(response::HTTP_OK , 'email sended successfully' , ['code' => $code]);
    }

    public function resetPassword(PasswordRequest $request)
    {
        $password = $request->validated()['password'];

        hashing_password($password);

        Admin::adminName()->update([
            'password' => $password
        ]);

        return $this->SendResponse(response::HTTP_OK , 'password updated successfully');
    }
}