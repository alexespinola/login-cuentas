<?php

namespace Tests;

use loginCuentas\LoginCuentasServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase /* \PHPUnit\Framework\TestCase */
{

  public function setUp(): void 
  {
    parent::setUp();
    // additional setup
  }

  protected function getPackageProviders($app)
  {
    return [
      LoginCuentasServiceProvider::class,
    ];
  }

  protected function getEnvironmentSetUp($app)
  {
    // perform environment setup
  }

}