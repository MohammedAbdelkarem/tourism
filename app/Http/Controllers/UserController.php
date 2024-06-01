<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Admin;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\User\EmailRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\User\EditInfoRequest;
use App\Http\Requests\Auth\User\InformationRequest;


class UserController extends Controller
{

    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:user', ['except' => ['login', 'register' , 'sendCode']]);
    }
    
    public function sendCode(EmailRequest $request)
    {
        
        /**
         * validation: check if the email is not in the database , or : in the database and deleted at is not null
         * 
         * send the verification code via the email
         * 
         * store the code in the cache for one hour , also the email
         * 
         * response
         */

        $email = $request->validated()['email'];

        $exists = DB::table('users')
                    ->where('email', $email)
                    ->exists();

        $code = RandomCode();

        Cache::forever('user_email' , $email);
        Cache::put('code' , $code , now()->addHour());

        event(new SendEmailEvent($email , $code));

        if($exists)
        {
        User::where('email' , $email)->update([
            'active' => 'active'
        ]);
        }
        else
        {
        User::create([
            'email' => $email
            ]);
        }
        return $this->SendResponse(response::HTTP_OK , 'email sended successfully');
    }

    public function register(InformationRequest $request)
    {
        $validatedData = $request->validated();

        hashing_data($validatedData);
        
        User::where('email' , user_email())->update($validatedData);

        return $this->SendResponse(response::HTTP_CREATED , 'user registered successfully');
    }

    public function updateProfile(EditInfoRequest $request)
    {
        $validatedData = $request->validated();

        hashing_data($validatedData);
        
        User::where('email' , user_email())->update($validatedData);

        return $this->SendResponse(response::HTTP_CREATED , 'user profile updated successfully');
    }

    public function login(LoginRequest $request)
    {
        if (! $token = auth()->guard('user')->attempt($request->only('email' , 'password')))
        {
            return $this->SendResponse(response::HTTP_UNAUTHORIZED , 'wrong email or password');
        }

        Cache::forever('user_email' , $request->email);

        return $this->SendResponse(response::HTTP_OK , 'logged in successfully' ,['token' => $token]);
    }

    public function logout()
    {
        /**
         * the logout logic from admin controller
         * 
         * response
         */
    }

    public function resetPassword(Request $request)
    {
        /**
         * the same logic in the admin
         */
    }

    public function profile()
    {
        /**
         * 
         * return the user information
         */
    }
}

