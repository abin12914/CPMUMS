<?php

namespace App\Repositories;

use App\Models\User;
use Auth;
use Hash;

class UserRepository
{
    /**
     * Action for updating user profile
     */
    public function updateProfile($inputArray=[])
    {

        if(Hash::check($inputArray['currentPassword'], Auth::User()->password)) {
            $user = Auth::User();

            $user->username     = $inputArray['username'];
            $user->name         = $inputArray['name'];
            $user->email        = $inputArray['email'];
            
            if(!empty($inputArray['password'])) {
                $user->password     = Hash::make($inputArray['password']);
            }

            if($user->save()) {
                return [
                    'flag'  => true,
                ];
            } else {
                return [
                    'flag'  => false,
                    'error' => "Invalid input!"
                ];
            }
        }
        return [
            'flag'  => false,
            'error' => "Invalid password!"
        ];
    }
}
