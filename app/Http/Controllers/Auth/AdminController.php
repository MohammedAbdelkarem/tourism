<?php
namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use App\Event\SendEmailEvent;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Admin\EmailRequest;
use App\Http\Requests\Auth\Admin\NameLoginRequest;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
class AdminController extends Controller
{

    use ResponseTrait;
    public function login(NameLoginRequest $request)
    {
        if (! $token = auth()->attempt($request->only('name' , 'password')))
        {
            return $this->SendResponse(response::HTTP_UNAUTHORIZED , 'wrong name or password');
        }

        Cache::forever('admin_name' , $request->name);

        $admin = Admin::where('name', $request->name)->first();

        
        Cache::forever('admin_ratio', 10); // store the fixed value 10 in the cache
        
        return $this->SendResponse(response::HTTP_OK , 'logged in successfully' ,['token' => $token, 'role' => $admin->role]);
    }

    public function storeEmail(EmailRequest $request)
    {
        $email = $request->validated()['email'];

        Cache::forever('admin_email' , $request->email);

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
    public function sendCode(EmailRequest $request)
    {
        $email = $request->validated()['email'];

        $code = RandomCode();

        Cache::put('code', $code , now()->addHour());

        event(new SendEmailEvent($email , $code));

        Cache::forever('admin_email', $email);

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


    public function updateAdminRatio(Request $request)
    { 
        $ratio = $request->input('ratio');
        Cache::forever('admin_ratio', $ratio);
        return $this->SendResponse(response::HTTP_OK , 'Admin ratio updated successfully');
    
    }

}