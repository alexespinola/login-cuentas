<?php

namespace loginCuentas\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Firebase\JWT\JWT;
use loginCuentas\Helpers\OAuth2CuentasHelper;
use loginCuentas\Events\UserWasLogged;
use Redirect;
use Session;
use Config;


class LoginController extends Controller {

  /** constructor */
  public function __construct() {   
    $this->provider = OAuth2CuentasHelper::get_provider();
  }


  /** Show login form */
  public function showLoginForm(Request $request)  {
    session(['accessToken'=>null]); // borro acá el accessToken porque en el logout no se borra si hago el redirect
    return view('loginCuentas::auth.login');
  }


  /** Login contra cuentas*/
  public function login(Request $request)  {   
    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters (e.g. state).
    $authorizationUrl = $this->provider->getAuthorizationUrl();
    // Get the state generated for you and store it to the session.
    Session::put('oauth2state', $this->provider->getState());
    // Redirect the user to the authorization URL.
    return Redirect::to($authorizationUrl);
  }


  /** authorize - callback de cuentas */
  public function authorize2(Request $request)  {
    // Check given state against previously stored one to mitigate CSRF attack
    if ( !$request->has('state') || ( $request->input('state') !== Session::get('oauth2state') )) {
      Session::flush('oauth2state');
      return Redirect::to('login')->with('_method', 'POST');
      // exit('Invalid state');
    } 
  
    try {
      // Try to get an access token using the authorization code grant.
      $accessToken = $this->provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
      ]);
      // We have an access token, which we may use in authenticated requests against the service provider's API.
      session(['accessToken' => $accessToken]);
      //Gurada el token en session
      session(['token' => $accessToken->getToken()]);
      //valida y decodifica el token 
      $decodedToken = $this->validateToken($accessToken);
      //autentica al usuario
      $this->authenticate($decodedToken);
      //si se autentico con exito
      return redirect(Config::get('loginCuentas.urlRedirectAfterLogin'));
    } 
    catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
      // Failed to get the access token or user details.
      exit($e->getMessage());
    }
  }

  
  /** Decodifica el token y lo guarda en session */
  public function validateToken($accessToken) {
    try {
      $token = $accessToken->getToken();
      $key = Config::get('loginCuentas.publicKey');
      JWT::$leeway = 90;
      $decoded = JWT::decode($token, $key, ['RS256']);
      session(['jwtToken' => json_encode($decoded, JSON_PRETTY_PRINT)]);
      return $decoded;
    } 
    catch (Exception $e) {
      dd($e->getMessage());
    }
  }


  /** Loguea al usuario con el sistema de autentificacion de laravel */
  public function authenticate($decodedToken) {
    try {
      // login user in laravel 
      $userID = $decodedToken->user->id;
      Auth::loginUsingId((int)$userID);
      // Emite el evento UserWasLogged para poder realizar acciones cuando el user se logea
      event(new UserWasLogged($userID));
    }
    catch (Exception $e) {
      dd($e->getMessage());
    }
  }


  /** Logout */
  public function logout(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->flush(); 
    // return Redirect::to('/login');
    $url_logout = 'https://cuenta.sofse.gob.ar/auth/logout?next='.url('/login');
    header('Location: ' . $url_logout);
    exit;
  }

}