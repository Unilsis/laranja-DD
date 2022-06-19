<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
	public function sendResponse($result, $message, $status)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
            'status' => $status
        ];

        return response()->json($response, $status);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $status)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'status' => $status
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $status);
    }
}
