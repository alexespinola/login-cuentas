### LoginCuentas
<b>loginCuentas</b> es una librería que le permite integrar la autenticación de Laravel al sistema de cuentas de de Trenes Argentinos.

Esta librería genera todas las rutas, vistas, controladores, etc, necesarios para el proceso de autenticación, por lo tanto usted no debe usar el comando que provee Laravel `php artisan make:auth` a no ser que su aplicación no deba loguearse con el sistema de cuentas. En tal caso, no debe usar esta librería.

Internamente se usa el protocolo de autenticación Oauth2 mediante el package ["league/oauth2-client"](https://packagist.org/packages/league/oauth2-client) 

#### Requerimientos
- laravel: ^7
- composer 

#### Instalacion

Desde la terminal (dentro de su proyecto): 
`composer require alexespinola/login-cuentas`

En el archivo  `.env` de su aplicaion defina las credenciales de autenticación 

- `clientId=<clientId>`
- `clientSecret=<clientId>` 
- `redirectUri=http://<path-to-app>/authorize`
- `urlAuthorize=https://cuenta.sofse.gob.ar/oauth2/authorize/`
- `urlAccessToken=https://cuenta.sofse.gob.ar/oauth2/token/`
- `urlResourceOwnerDetails=https://cuenta.sofse.gob.ar/api/user/`

#### Archivo de configuración
publique la config de este paquete con el siguiente comando:

`php artisan vendor:publish --provider="loginCuentas\LoginCuentasServiceProvider" --tag="config"`

Esto crea un archivo de configuracion en su aplicación: `config/loginCuentas.php`
En este archivo usted puede definir la URL a donde redirigir despues del login modificando el valor de la clave `'urlRedirectAfterLogin'`.

#### Evento UserWasLogged 
Es probable que usted necesite realizar acciones cuando un usuario se loguea en su aplicacíon como por ejemplo:
  - Guardar los permisos del usuario en una variable de sesión
  - Registrar los accesos a la aplicación

Para esto el paquete <b>LoginCuentas</b> emite un evento <b>UserWasLogged</b> para que usted pueda registrar un Listener en su aplicación que escuche dicho evento y realice las acciones que usted desee. 

##### Instruciones para implementes el Listener

1- Cree el listener  `App\Listeners\afterUserLogged.php` y codifique las acciones que desee en el método <b>handle</b>. 
NOTE que la la variable <b>$event->userID</b> contien el ID del usuario logueado.

```php
<?php

namespace App\Listeners;

use loginCuentas\Events\UserWasLogged;
use App\User;
use Session;

class afterUserLogged
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
      
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserWasLogged  $event
     * @return void
     */
    public function handle(UserWasLogged $event)
    {
        //COMO EJEMPLO: define una variable de sección con los datos del usuario
        $user = User::find($event->userID)->toJson();
        Session::put('user', $user);
    }
}
```

2- registre el Event y el Listener en el archivos `App\Providers\EventServiceProvider.php` dentro del array `protected $listen`:

```php
use loginCuentas\Events\UserWasLogged;
use App\Listeners\afterUserLogged;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserWasLogged::class =>[
            afterUserLogged:: class,
        ]
    ];

    ...
```

NOTE que se importan el evento desde el paquete <b>loginCuentas</b> y el listener que usted creó desde su aplicación. 

#### publicar vistas
Si usted quiere modificar las vistas que provee este paquete puede puplicarlas con el siguiente comando: 

`php artisan vendor:publish --provider="loginCuentas\LoginCuentasServiceProvider" --tag="views"`

Esto crea una carpeta con todas las vistas en su aplicación en `resources/views/vendor/loginCuentas`

