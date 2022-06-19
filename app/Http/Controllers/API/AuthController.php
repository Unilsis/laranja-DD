<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Validator;
use App\Models\User;

class AuthController extends ResponseController
{
    /**
    * @OA\Post(
    * path="/api/login",
    * operationId="authLogin",
    * tags={"login"},
    * summary="Connect to API",
    * description="Endpoint to connect to API",
    *
    *  @OA\Parameter(
    *      description="Nome de usuário",
    *      name="name",
    *      in="query",
    *      required=true,
    *      example="Domingos Dias",
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *  @OA\Parameter(
    *      name="email",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *  @OA\Parameter(
    *      name="password",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *      @OA\Parameter(
    *      name="confirm_password",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
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

    public function signin(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $authUser = Auth::user(); 
            $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken; 
            $success['name'] =  $authUser->name;
   
            return $this->sendResponse($success, 'User signed in', 200);
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 401);
        } 
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors(), 400);       
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
        $success['name'] =  $user->name;
        $success['email'] =  $user->email;

        return $this->sendResponse($success, 'User created successfully.', 200);
    }
}
