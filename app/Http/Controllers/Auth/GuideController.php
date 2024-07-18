<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use App\Models\Guide;
use App\Models\UsersBackup;
use Illuminate\Http\Request;
use App\Event\SendEmailEvent;
use App\Traits\ResponseTrait;
use App\Models\Guides_backups;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordRequest;
use App\Http\Resources\User\ProfileResource;
use App\Http\Requests\Auth\User\EmailRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Notifications\AdminNotification;
use App\Http\Requests\Auth\Guide\EmailRequest as GuideEmailRequest;
use App\Http\Resources\Guide\ProfileResource as GuideProfileResource;
use App\Http\Requests\Auth\Guide\EditInfoRequest as GuideEditInfoRequest;
use App\Http\Requests\Auth\Guide\InformationRequest as GuideInformationRequest;

class GuideController extends Controller
{
    use ResponseTrait;
    
    public function sendCode(GuideEmailRequest $request)
    {
        $email = $request->validated()['email'];

        $code = RandomCode();

        Cache::forever('guide_email' , $email);
        Cache::put('code' , $code , now()->addHour());

        event(new SendEmailEvent($email , $code));

        return $this->SendResponse(response::HTTP_OK , 'email sended successfully');
    }

    public function register(GuideInformationRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['photo'] = photoPath($validatedData['photo']);
        // $validatedData['email'] = guide_email();
        $validatedData['email'] = "mayagritaabouasali@gmail.com";
        hashing_data($validatedData);
        
        $guide = Guide::create($validatedData);
        Guides_backups::create($validatedData);

         // Send notification to all admins
        $adminNotification = new AdminNotification();
        $adminNotification->sendNotificationIfNewGuideRegistered($guide);

        return $this->SendResponse(response::HTTP_CREATED , 'guide registered successfully');
    }

    public function updateProfile(GuideEditInfoRequest $request)
    {
        $validatedData = $request->validated();

        hashing_data($validatedData);

        $validatedData['photo'] = photoPath($validatedData['photo']);

        Guide::where('email' , guide_email())->update($validatedData);
        Guides_backups::where('email' , guide_email())->update($validatedData);

        return $this->SendResponse(response::HTTP_CREATED , 'guide profile updated successfully');
    }

    public function login(LoginRequest $request)
    {
        if (! $token = auth()->guard('guide')->attempt($request->only('email' , 'password')))
        {
            return $this->SendResponse(response::HTTP_UNAUTHORIZED , 'wrong email or password');
        }

        Cache::forever('guide_email' , $request->email);

        return $this->SendResponse(response::HTTP_OK , 'logged in successfully' ,['token' => $token]);
    }

    public function logout()
    {
        auth()->guard('guide')->logout();
        
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
        $data = auth()->guard('guide')->user();

        $data = new GuideProfileResource($data);
        
        return $this->SendResponse(response::HTTP_OK , 'guide profile data retrieved successfully' , $data);
    }

    public function deleteAccount()
    {
        $this->logout();
        
        Guide::guideEmail()->delete();

        return $this->SendResponse(response::HTTP_OK , 'account deleted successfully');
    }


}
