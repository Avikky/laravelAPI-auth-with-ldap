<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Utility\JWT;
use Illuminate\Support\Facades\Auth;

class ValidateJWT
{
  
    public function handle(Request $request, Closure $next)
    {

        // if(Auth::check()){
        //     return Auth::user();
        // }else{
        //     return response()->json($request->bearerToken());
        // }
        $getToken = $request->bearerToken();
    
        $validate = JWT::validateToken($getToken);
        if($validate === FALSE){
            return response()->json(['status' => false, 'message' => 'unauthorized'], 401);
        }
        

        return $next($request);
    }
}
