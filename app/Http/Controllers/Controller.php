<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    
    public function successResponse($message = 'Success',$code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => [],
        ], $code);
    }

    public function successResponseWithData($data=[],$message='success', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }


    public function errorResponse($error, $code = 404, $errorMessages = [])
    {
        $response = [
            'status' => false,
            'message' => $error
        ];

        if(!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
