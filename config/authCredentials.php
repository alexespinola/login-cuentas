<?php

return [
  /** define las credenciale de autenticación y url de redireccion para cuentas */
  'clientId' => env('clientId'),
  'clientSecret' => env('clientSecret'),
  'redirectUri' => env('redirectUri'),
  'urlAuthorize' => env('urlAuthorize', 'https://cuenta.sofse.gob.ar/oauth2/authorize/'),
  'urlAccessToken' => env('urlAccessToken', 'https://cuenta.sofse.gob.ar/oauth2/token/'),
  'urlResourceOwnerDetails' => env('urlResourceOwnerDetails', 'https://cuenta.sofse.gob.ar/api/user/'),
  'urlApiCuentas' =>  env('urlApiCuentas', 'https://cuenta.sofse.gob.ar/api/'),
  'verify' => false,
];



