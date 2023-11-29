<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Validator;
use Laravel\Sanctum\HasApiTokens;
class AuthController extends BaseController
{

    use HasApiTokens, HasFactory, Notifiable;
    /**
    * Create user
    *
    * @param  [string] name
    * @param  [string] email
    * @param  [string] password
    * @param  [string] password_confirmation
    * @return [string] message
    */
    public function register(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $user = new User([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        if($user->save()){
            $tokenResult = $user->createToken('Personal Access Token');
            $res_data['accessToken'] = $tokenResult->plainTextToken;
            $res_data['token_type'] = 'Bearer';
            $res_data['message'] = 'Successfully created user!';
            return $this->sendResponse($res_data, 'User register successfully.');
           
        }
        else{
            return $this->sendError('Provide proper details', [], 401); 
           
        }
    }

/**
 * Login user and create token
*
* @param  [string] email
* @param  [string] password
* @param  [boolean] remember_me
*/
    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required',
        'password' => 'required',
    ]);
    if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());       
    }
    $credentials = request(['email','password']);
    if(!Auth::attempt($credentials))
    {
        return $this->sendError('Unauthorized', [], 401);    
   
    }

    $user = $request->user();
    $tokenResult = $user->createToken('Personal Access Token');
    $res_data['accessToken'] = $tokenResult->plainTextToken;
    $res_data['token_type'] = 'Bearer';
    return $this->sendResponse($res_data, 'Successfully Logged In!');
}


/**
 * Logout user (Revoke the token)
*
* @return [string] message
*/
public function logout(Request $request)
{
   
    Auth::user()->tokens->each(function($token, $key) {
        $token->delete();
    });
    
    return $this->sendResponse([], 'Successfully logged out');
   

}


}
