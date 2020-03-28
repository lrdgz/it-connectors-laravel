<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    use ApiResponse;

    public function verify( $token ){

        //TODO: find the user account that has that token
        $user = User::where(['email_token' => $token])->first();

        if(!$user){
            return $this->specialResponse([
                'access_token'  => null,
                'token_type'    => null,
                'expires_at'    => null,
                'status'        => 'Error',
                'code'          => 404,
                'message'       => 'User not found.',
                'data'          => []
            ],404);
        }

        //TODO: if exists then we verify the email
        $user->verified_email = true;
        $user->active = 1;
        $user->email_verified_at = Carbon::now();
        $user->email_token = null;
        $user->save();

        return $this->specialResponse([
            'access_token'  => null,
            'token_type'    => null,
            'expires_at'    => null,
            'status'        => 'Successfully',
            'code'          => 200,
            'message'       => 'Thank you your email is verified.',
            'data'          => []
        ],200);
    }
}
