<?php
namespace  App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\RegisterAuthRequest;
use  JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class  SpaAuthController extends Controller {
  public  $loginAfterSignUp = true;
  
  public  function  register(Request $request) {
    
    $user = new  User();
    $user->name = $request->name;
    $user->surname = $request->surname;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->save();
    
    if ($this->loginAfterSignUp) {
      return  $this->login($request);
    }
    
    return  response()->json([
      'status' => 'ok',
      'data' => $user
    ], 200);
  }
  
  public  function  login(Request  $request) {
    $request->validate([
      'email'    => 'required|string',
      'password' => 'required|string'
    ]);
    
    $input     = $request->only('email', 'password');
    $jwt_token = null;
    
    if (!$jwt_token = JWTAuth::attempt( $input ) ) {
      return response()->json([
        'status'  => 'invalid_credentials',
        'message' => 'Correo o contraseña no válidos.',
      ], 401);
    }
    
    $user = User::with('station')->where('email', $request->email)->first();
    $user->token = $jwt_token;
    
    return response()->json([
      'status' => 'ok',
      'user'   => $user
    ]);
  }
  
  public  function  logout(Request  $request) {
    $this->validate($request, [
      'token' => 'required'
    ]);
    
    try {
      JWTAuth::invalidate($request->token);
      return  response()->json([
        'status' => 'ok',
        'message' => 'Cierre de sesión exitoso.'
      ]);
    } catch (JWTException  $exception) {
      return  response()->json([
        'status' => 'unknown_error',
        'message' => 'No fue posible invalidar la sesión'
      ], 500);
    }
  }
  
  public  function  getAuthUser(Request  $request) {
    $this->validate($request, [
      'token' => 'required'
    ]);
    
    $user = JWTAuth::authenticate($request->token);
    return  response()->json(['user' => $user]);
  }
}
?>