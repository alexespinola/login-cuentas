<?php

namespace loginCuentas\Http\Middleware;

use Closure;
use \Firebase\JWT\JWT;
use Config;

class CheckToken {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next) {
    try {
      /** si recive X-API-KEY */ 
      // if($request->header('X-API-KEY')) {
      //   if($request->header('X-API-KEY') == env('X_API_KEY')){
      //     return $next($request);
      //   }
      // }

    
      if($request->header('Authorization')) {
        $key = Config::get('loginCuentas.publicKey');
        $jwt = $request->header('Authorization');
        /* Intento decodificarlo */
        $decoded = JWT::decode($jwt, $key, ['RS256']); //si no puede decodificarlo lanza una excepcion
      }
      
      return $next($request);  
    } 
    catch (\Exception $e) {
      /* Si no pudo decodificar el token devuelvo error */
      return response()->json(['status'=>401, 'error' => 'Token invalido'], 401);
    }
  }

}
