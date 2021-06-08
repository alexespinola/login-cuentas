<?php

return [

  /** define las credenciale de autenticación y url de redireccion para cuentas */
  'clientId' => env('clientId'),
  'clientSecret' => env('clientSecret'),
  'redirectUri' => env('redirectUri'),
  'urlAuthorize' => env('urlAuthorize'),
  'urlAccessToken' => env('urlAccessToken'),
  'urlResourceOwnerDetails' => env('urlResourceOwnerDetails'),
  'verify' => false,

  /** define la URL de redirección despues del login */
  'urlRedirectAfterLogin' => 'home',
];


