<?php

namespace loginCuentas\Http\Middleware;

use loginCuentas\Helpers\OAuth2CuentasHelper;
use Closure;
use Config;
use Exception;

class RefreshToken {

  public function __construct() {  

  }

  /**
   * Handle an incoming request.
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next) { 
    
    // si el token no está en sesion y no es una peticion AJAX redirijo al login
    $existingAccessToken = session('accessToken');
    if(!$existingAccessToken && !$request->expectsJson()) {
      return redirect('/login');
    }
    // si el token no está en sesion y es una peticion AJAX redirijo al login
    if(!$existingAccessToken && $request->expectsJson()) {
      return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    //obtiene un token nuevo y lo pone en seccion
    OAuth2CuentasHelper::refreshToken();
    return $next($request);
  }
}
