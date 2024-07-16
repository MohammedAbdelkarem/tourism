<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Event\SendEmailEvent;
use App\Traits\ResponseTrait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordRequest;
use App\Http\Resources\User\ProfileResource;
use App\Http\Requests\Auth\User\EmailRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\User\EditInfoRequest;
use App\Http\Requests\Auth\User\InformationRequest;
use App\Http\Requests\IdRequest;
use App\Models\UsersBackup;

class UserController extends Controller
{
    use ResponseTrait;
    
    public function sendCode(EmailRequest $request)
    {
        $email = $request->validated()['email'];

        $code = RandomCode();

        Cache::forever('user_email' , $email);
        Cache::put('code' , $code , now()->addHours(1));

        event(new SendEmailEvent($email , $code));

        return $this->SendResponse(response::HTTP_OK , 'email sended successfully');
    }

    public function register(InformationRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['email'] = user_email();

        hashing_data($validatedData);
        
        User::create($validatedData);
        UsersBackup::create([
            'name' =>$validatedData['name'],
            'phone' =>$validatedData['phone'],
            'email' =>$validatedData['email'],
        ]);

        return $this->SendResponse(response::HTTP_CREATED , 'user registered successfully');
    }

    public function updateProfile(EditInfoRequest $request)
    {
        $validatedData = $request->validated();
        if($request->hasFile('photo'))
        {
            $validatedData['photo'] = photoPath($validatedData['photo']);
        }

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
        auth()->guard('user')->logout();
        
        return $this->SendResponse(response::HTTP_OK , 'logged out successfully');
    }

    public function resetPassword(PasswordRequest $request)
    {
        $password = $request->validated()['password'];

        hashing_password($password);

        User::userEmail()->update([
            'password' => $password
        ]);

        return $this->SendResponse(response::HTTP_OK , 'password updated successfully');
    }

    public function profile()
    {
        $data = auth()->guard('user')->user();

        // $data = new ProfileResource($data);
        
        return $this->SendResponse(response::HTTP_OK , 'user profile data retrieved successfully' , $data);
    }

    public function deleteAccount()
    {
        UsersBackup::userEmail()->update([
            'active' => 'inactive'
        ]);


        $this->logout();
        
        User::userEmail()->delete();

        Cache::forget('user_email');

        return $this->SendResponse(response::HTTP_OK , 'account deleted successfully');
    }


    public function deletePhoto()
    {
        $user_id = user_id();

        $user = User::find($user_id);

        $user->photo = null;
        $user->save();

        return $this->SendResponse(response::HTTP_OK , 'photo deleted successfully');
    }
}
