<?php

namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
    use ResponseTrait;
    
    public function sendEmail(Request $request)
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
