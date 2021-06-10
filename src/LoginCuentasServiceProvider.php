<?php

namespace loginCuentas;

use Illuminate\Support\ServiceProvider;

class LoginCuentasServiceProvider extends ServiceProvider
{
  
  public function register()
  {
    $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'loginCuentas');
    $this->mergeConfigFrom(__DIR__.'/../config/authCredentials.php', 'loginCuentas');
    $this->mergeConfigFrom(__DIR__.'/../config/publicKeyCuentas.php', 'loginCuentas');
  }

  public function boot()
  {
    $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    $this->loadViewsFrom(__DIR__.'/../resources/views', 'loginCuentas');
    
    if ($this->app->runningInConsole()) {
      // Publish views
      $this->publishes([ __DIR__.'/../resources/views' => resource_path('views/vendor/loginCuentas')], 'views');
      // Publish config
      $this->publishes([__DIR__.'/../config/config.php' => config_path('loginCuentas.php')], 'config');
    }
  }

}
