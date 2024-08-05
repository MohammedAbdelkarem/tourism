<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UsersBackup;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\UserTransaction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Notifications\GuideNotificationService;
use App\Services\Notifications\UserNotificatoinService;

class UsersController extends Controller
{
    use ResponseTrait;

    private UserNotificatoinService $userNotificatoinService;
 
    public function __construct(UserNotificatoinService $userNotificatoinService)
    {
        $this->userNotificatoinService = $userNotificatoinService;
    }
    public function getUsers(){
        $users = User::all();
        $data= UserResource::collection($users);

        return $this->SendResponse(response::HTTP_OK, 'users retrieved successfully',$data);
    }



 public function getUserDetails($userId){
        $user = User::where('id', $userId)->get();
        // $data= UserResource::collection($users);
        return $this->SendResponse(response::HTTP_OK, 'user retrieved successfully',$user);
    }



    public function addToWallet($userId)
    {
        
        $latestTransaction = UserTransaction::where('user_id', $userId)
            ->latest()
            ->first();
    
        // Calculate the new wallet value
        $newWalletValue = ($latestTransaction ? $latestTransaction->wallet : 0) + request('amount');
    
        // Create a new UserTransaction record
        $userTransaction = UserTransaction::create([
            'wallet' => $newWalletValue,
            'date' => now(),
            'type' => 'add',
            'amount' => request('amount'),
            'user_id' => $userId,
            'reservation_id' => null,
            'admin_id' => admin_id(),
        ]);
    
        $userBackup = UsersBackup::where('email', User::find($userId)->email)->first();

        // Update the wallet column of the UsersBackup instance
        if ($userBackup) {
            $userBackup->wallet = $newWalletValue;
            $userBackup->save();
        }


        $this->userNotificatoinService->SendWalletNotification(
            $userId,
            request('amount'),
            'add'
        );
        return $this->SendResponse(response::HTTP_OK, 'Wallet updated successfully',['wallet' => $newWalletValue]);
    }
}
