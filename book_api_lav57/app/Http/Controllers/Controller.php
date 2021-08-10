<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function sendResponse($result, $message = "", $code = Response::HTTP_OK)
    {
        
        return response()->json($result, $code, array(), JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
        
        //return Response::json(ResponseUtil::makeResponse($message, $result), $code, [], JSON_UNESCAPED_SLASHES);
    }
    
    
    public function sendError($error, $code = 400, $suppress = 0)
    {
        if($code < 199 || $code > 500) {
            $code = 500;
        }
        if($suppress == 1){
            $code = 200;
        }

        $response = array(  
            'message' => $error
        );


        return response()->json($response, $code);
    }
    
}
