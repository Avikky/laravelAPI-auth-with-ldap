<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Utility\JWT;
use Adldap\Laravel\Facades\Adldap;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {
      
        $email = $request->email;
        $password = $request->password;

        if (!Auth::attempt(['email' => $email, 'password' => $password], true)){
            return $this->errorResponse('Invalid credentials');
        }

        
        $user = Auth::user();
        $token = $user->createToken('demandPortalToken');

        Auth::login($user);


        $user['access_token'] = $token->accessToken;
        $time = Carbon::now()->addMinutes(30)->toDateTimeString();
        $user['token_expires_at'] = strtotime($time) * 1000;


        return $this->successResponseWithData($user, 'login successfull');

    }

    public function refreshToken(Request $request)
    {
        $user = Auth::user();
        $newToken = $user->createToken('demandPortalToken');

        $data['access_token'] = $newToken->accessToken;
        $time = Carbon::now()->addMinutes(30)->toDateTimeString();
        $data['expires_at'] = strtotime($time) * 1000;

       return $this->successResponseWithData($data, 'token refreshed successfully');
    }


    public function logout()
    {

        $user = Auth::user()->token();
        $user->revoke();
        $user->delete();

        Session::flush();
        $provider = Adldap::getProvider('default');
        $provider->getConnection()->close();

        return $this->successResponse('Successfully logged out');
    }

}
