# LoginCuentas
<b>loginCuentas</b> es una librería que le permite integrar la autenticación de Laravel al sistema de cuentas de de Trenes Argentinos.

Esta librería genera todas las rutas, vistas, controladores, etc, necesarios para el proceso de autenticación, por lo tanto usted no debe usar el comando que provee Laravel `php artisan make:auth` a no ser que su aplicación no deba loguearse con el sistema de cuentas. En tal caso, no debe usar esta librería.

Internamente se usa el protocolo de autenticación Oauth2 mediante el package ["league/oauth2-client"](https://packagist.org/packages/league/oauth2-client) 

## Requerimientos
- laravel 7
- composer 

## Instalacion
En el archivo composer.json de su aplicación agregre lo siguiente debajo de la clave "scripts";

` "scripts": {...},
  "repositories": [{
    "type": "path",
    "url": "../packages/loginCuentas"
  }]
` 

Luego instale con composer
`composer require alexespinola/login-cuentas`

### Para publicar las vistas

php artisan vendor:publish --provider="loginCuentas\LoginCuentasServiceProvider" --tag="views"

### publicar config

php artisan vendor:publish --provider="loginCuentas\LoginCuentasServiceProvider" --tag="config"