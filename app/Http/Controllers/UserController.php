<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Event\SendEmailEvent;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Auth\User\EmailRequest;
use App\Http\Requests\Auth\User\InformationRequest;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
    use ResponseTrait;
    
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

        hashing_password($validatedData()->password);
        
        User::where('email' , user_email())->update($validatedData);

        return $this->SendResponse(response::HTTP_CREATED , 'user registered successfully');
    }

    public function updateProfile(Request $request)
    {
        /**
         * validation: we can edit everything expect the email ,everything is required
         * 
         * go and update the entered data
         * 
         * response
         * 
         * this method is gonna used as a register complete and in edit profile
         */
    }

    public function login(Request $request)
    {
        /**
         * validation:we can use the request file which is the loginRequst file
         * 
         * check if the email and password matches
         * 
         * in case matching: store the email in the cache
         * 
         * response with the token
         */
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
         * return the user information
         */
    }
}
