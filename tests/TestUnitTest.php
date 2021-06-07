<?php

namespace Tests;

use loginCuentas\Calculator;

class TestUnitTest extends TestCase
{
  /** 
   * @test 
   * para saber que funcion PHPUnit
  */
  function test_unit_test()
  {
    $this->assertTrue(true);
  }

  /** 
   * @test }
   * Para comprobar que carga correctamente las clases de src
  */
  public function test_calculator()
  {
    $calculator = new Calculator();
    $sum = $calculator->sum(7,8);
    $this->assertSame(15, $sum);
  }
}