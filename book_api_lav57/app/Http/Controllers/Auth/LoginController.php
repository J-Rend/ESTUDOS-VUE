<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User; 

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
      public function defaultMessage(Request $request){

      
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        //Artisan::call('config:cache');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        
        /*
         *      php artisan config:clear
                php artisan cache:clear
                php artisan view:clear
                php artisan route:clear
                composer dump-autoload
         */
        
          //session_destroy();
         //$request->session()->flush();
         return $this->sendResponse( array("msg" => env("DB_HOST"),"Use o método POST ". " - ". date("Y-m-d H:i:s")));
    }
    
    
    
    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response($validator->errors());
        }


        $credentials = $request->only('email', 'password');
        
        
        return $this->loginSenha($credentials["email"], $credentials["password"], "", $request);        
      
        // $user->setAttribute('refresh', $refresh_token);

    }
    
      public function loginSenha($email, $password, $encriptado = "", $request){
        
        $user = User::where("email", trim($email) )->first();
        
         if (!$user) {
             return $this->sendError('Usuário ou senha inválidos ' . $email );
         }

         if ( $request->input("debug") == "1"){
                print_r(  $user ); die(" ");
         }
        
       
        
        $credentials = array();
        $credentials['email'] = $user->email;
        $credentials['password'] = $password;
        
        if (!Auth::attempt($credentials)) {
                        return $this->sendError('Usuário ou senha inválidos');
        }
 
         
         
         if ( is_null($user->api_token) || $user->api_token == ""){
                     $token = $user->createToken('access_token')->accessToken;


                     $user->setAttribute('access_token', $token);
                     $user->setAttribute('api_token', $token);
	             $user->save();
         }
         
         $saida = array("id"=>$user->id,
                         "name"=>$user->name,"email"=>$user->email, 
                         "token"=>$user->api_token);
         
         
         return $this->sendResponse(  $saida );

    }
    
}
