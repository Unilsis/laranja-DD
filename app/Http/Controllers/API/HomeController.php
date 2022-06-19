<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Models\Custumer;
 

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

    /**
    * @OA\Get(
    * path="/api/findCustomerById/{id}",
    * operationId="findCustomerById",
    * tags={"findCustomerById"},
    * summary="Find Custumer",
    * description="find customer by id",
    *  @OA\Response(
    *      response=200,
    *      description="User signed in",
    *      @OA\JsonContent()
    *  ),
    *  @OA\Response(response=400, description="Bad request"),
    *  @OA\Response(response=401, description="Unauthorised"),
    *  @OA\Response(response=404, description="Resource Not Found"),
    * )
    */
    public function findCustomer($id)
    {
        $custumers = Custumer::findOrFail($id);
        return $this->sendResponse($custumers, 'Success', 200);
    }

    /**
    * @OA\Get(
    * path="/api/findAllCustomer",
    * operationId="findAllCustomer",
    * tags={"findAllCustomer"},
    * summary="All customer",
    * description="FindA all customer",
    *  @OA\Response(
    *      response=200,
    *      description="User signed in",
    *      @OA\JsonContent()
    *  ),
    *  @OA\Response(response=400, description="Bad request"),
    *  @OA\Response(response=401, description="Unauthorised"),
    *  @OA\Response(response=404, description="Resource Not Found"),
    * )
    */
    public function findAllCustomer()
    {
        $custumers = Custumer::get();
        if ($custumers->isEmpty()) { 
            return $this->sendError('Not Found.', ['error'=>'Resources not found'], 404);
        }else{
            return $this->sendResponse($custumers, 'Success', 200);
        }
    }
}
