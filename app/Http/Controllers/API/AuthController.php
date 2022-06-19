<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Validator;
use App\Models\User;
use Mail;
use App\Mail\NotifyMail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class AuthController extends ResponseController
{

    /**
    * @OA\Post(
    * path="/api/login",
    * operationId="authLogin",
    * tags={"login"},
    * summary="Connect to API",
    * description="Endpoint to connect to API",
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

            $authUser->tokens()->delete();
            $data['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken; 
            $data['name'] =  $authUser->name;
   
            return $this->sendResponse($data, 'User signed in', 200);
        } 
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 401);
        }
    }

    /**
    * @OA\Post(
    * path="/api/login_mobile",
    * operationId="authLoginMobile",
    * tags={"login_mobile"},
    * summary="Connect to API",
    * description="Endpoint to connect to the API through a mobile application",
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
    *  @OA\Parameter(
    *      description="Nome do dispositvo",
    *      name="device_name",
    *      in="query",
    *      required=true,
    *      example="Nuno's iPhone 12",
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
    public function signin_mobile(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
 
        $user = User::where('email', $request->email)->first();
 
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Unauthorised', 'The provided credentials are incorrect.', 401);
        }
 
        return $user->createToken($request->device_name)->plainTextToken;
    }

    /**
    * @OA\Post(
    * path="/api/register",
    * operationId="signup",
    * tags={"register"},
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
    public function signup(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors(), 400);       
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $user->tokens()->delete();
        $data['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
        $data['name'] =  $user->name;
        $data['email'] =  $user->email;

        $emailsend = $this->sendmail($data['email'], $data['name']);

        return $this->sendResponse(
            $data,
            [
                'success'=> 'User created successfully.',
                'success_mail'=> $emailsend
            ] ,
            200
        );
    }

    public function sendmail($email, $custumer)
    {
      Mail::to($email)->send(new NotifyMail($custumer));
 
      if (Mail::failures()) {
         return response()->json(array(
            'message_mail' => 'Sorry! It was not possible to send the email'
         ));
      }else{
         return response()->json(array(
            'message_mail' => 'Great! Successfully send in your mail'
         ));
      }
    } 
}
