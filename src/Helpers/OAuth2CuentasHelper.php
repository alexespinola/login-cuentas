<?php

namespace loginCuentas\Helpers;

use League\OAuth2\Client\Provider\GenericProvider;
use \Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Exception;
use Auth;
use Redirect;
use Config;
use Session;

class OAuth2CuentasHelper {
  
	public function __construct() {
    // $this->url_api = 'https://cuenta.sofse.gob.ar/api/';
    // $this->accessToken = session('accessToken');
    $existingAccessToken = session('accessToken');
    if(!$existingAccessToken){
      return redirect('/login');
    }
	}

  /** return a provider instance */
  public static function get_provider() {
    return $provider = new GenericProvider([
      'clientId'                => Config::get('loginCuentas.clientId'),    
      'clientSecret'            => Config::get('loginCuentas.clientSecret'),
      'redirectUri'             => Config::get('loginCuentas.redirectUri'),
      'urlAuthorize'            => Config::get('loginCuentas.urlAuthorize'),
      'urlAccessToken'          => Config::get('loginCuentas.urlAccessToken'),
      'urlResourceOwnerDetails' => Config::get('loginCuentas.urlResourceOwnerDetails'),
      'verify'                  => Config::get('loginCuentas.verify')
    ]);
  }


  /** return de users from api usuarios */
  public static function getUsers() {
    $provider = self::get_provider();
    $request = $provider->getAuthenticatedRequest(
      'GET',
      Config::get('loginCuentas.urlApiCuentas').'users',
      session('accessToken')
    );

    $response = $provider->getParsedResponse($request);
    return  json_decode(json_encode($response));
  }


  /** Asocia un usuario al sistema  */
  public static function asociate($email) {
    $provider = self::get_provider();
    $request = $provider->getAuthenticatedRequest(
      'GET',
      Config::get('loginCuentas.urlApiCuentas').'associate?email='.$email,
      session('accessToken')
    );

    $response = $provider->getParsedResponse($request);
    return json_decode(json_encode($response));
  }


  /** Return the user object in the token */
	public static function user() {
    $token = json_decode(session('jwtToken'));
    return $token->user;
  }


  /** return the user full-name in the token */
  public static function user_name()  {
    $token = json_decode(session('jwtToken'));
    return $token->user->first_name.' '.$token->user->last_name;
  }


  /** return true if user is logged_in */
  public static function logged_in() {
    if(session('jwtToken')) {
      return true;
    }
    else {
      return false;
    }
  }


  /** renueva el token si expiró */
  public static function refreshToken() {
    $existingAccessToken = session('accessToken');
    // si el token expiro lo renuevo
    if ($existingAccessToken->hasExpired()) {
      $provider = self::get_provider();
      $newAccessToken = $provider->getAccessToken('refresh_token', [
        'refresh_token' => $existingAccessToken->getRefreshToken()
      ]);
      session(['accessToken' => $newAccessToken]);
    }
  }

}