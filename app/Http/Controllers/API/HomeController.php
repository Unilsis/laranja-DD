<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Models\Custumer;
 
use Mail;
use App\Mail\NotifyMail;
 

class HomeController extends ResponseController
{ 
    /**
    * @OA\Get(
    * path="/api/index",
    * operationId="Stard API",
    * tags={"index"},
    * summary="Api Start",
    * description="Api Start",
    *      @OA\Response(
    *          response=200,
    *          description="Success",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(response=400, description="Bad request"),
    *      @OA\Response(response=404, description="Resource Not Found"),
    * )
    */
	public function index()
    {
        return response()->json(['message' => 'WELCOME TO ORANGE API']);
    }

    public function findCustomer($id)
    {
        $custumers = Custumer::findOrFail($id);
        return $this->sendResponse($custumers, 'Success', 200);
    }

    public function findAllCustomer()
    {
        $custumers = Custumer::get();
        if ($custumers->isEmpty()) { 
            return $this->sendError('Not Found.', ['error'=>'Resources not found'], 404);
        }else{
            return $this->sendResponse($custumers, 'Success', 200);
        }
    }

    public function sendmail(Request $request)
    {
 
      Mail::to($request->email)->send(new NotifyMail($request->custumer));
 
      if (Mail::failures()) {
         return response()->json(array(
            'status' => 400,
            'message' => 'Sorry! It was not possible to send the email'
         ));
      }else{
         return response()->json(array(
            'status' => 200,
            'message' => 'Great! Successfully send in your mail'
         ));
      }
    } 
}
